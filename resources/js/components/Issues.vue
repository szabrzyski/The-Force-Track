<script setup>

import { ref } from 'vue';
import { useGlobalStore } from '../stores/globalStore.js';
import Alert from './partials/Alert.vue';

const emit = defineEmits(['viewLoaded']);

const globalStore = useGlobalStore();

const loadingInProgress = ref(true);
const issuesLoadingInProgress = ref(true);
const issuesLoadingController = ref(null);
const issues = ref([]);
const statuses = ref([]);
const selectedStatuses = ref([]);

function initialize() {
    loadingInProgress.value = true;
    let axiosResponse = axios({
        method: 'GET',
        url: '/initializeIssues',
        timeout: 30000,
    })
        .then((response) => {
            statuses.value = response.data;
            loadIssues();
        })
        .catch(function (error) {
            globalStore.handleError(error);
        })
        .then(function () {
            loadingInProgress.value = false;
            emit('viewLoaded');
        });

}

async function loadIssues(page = 1, scroll = false) {
    if (scroll) {
        globalStore.scrollToTop(false);
    }

    issuesLoadingInProgress.value = true;

    if (issuesLoadingController.value) {
        issuesLoadingController.value.abort();
    }

    issuesLoadingController.value = new AbortController();
    let axiosResponse = await axios({
        signal: issuesLoadingController.value.signal,
        method: 'POST',
        url: '/loadIssues',
        timeout: 30000,
        data: {
            page: page,
        },
    })
        .then((response) => {
            issues.value = response.data;
        })
        .catch(function (error) {
            if (!axios.isCancel(error)) {
                globalStore.handleError(error);
            }
        })
        .then(function () {
            issuesLoadingInProgress.value = false;
        });
}

initialize();

</script>

<template>
    <div v-if="loadingInProgress" class="loading">
    </div>
    <template v-else>
        <div class="container">
            <div class="row justify-content-center mt-2 mt-sm-3">
                <Alert />
                <div class="col-6">
                    <h5 class="text-secondary">Issues: {{ issues.data ? issues.data.length : 0 }}
                    </h5>
                </div>
                <div class="col-6 text-end">

                    <h5 class="text-secondary d-inline me-2">Status:
                    </h5>
                    <div v-for="status in statuses" :key="status.id" class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" :id="status.id" :value="status.id"
                            v-model="selectedStatuses">
                        <label class="form-check-label" :for="status.id">{{ status.name }}</label>
                    </div>
                </div>
                <div class="col-12 mt-lg-3">
                    <div class="col-12">
                        <button type="button" v-on:click="loadIssues()" :disabled="issuesLoadingInProgress"
                            class="btn btn-success w-100">{{
                                issuesLoadingInProgress? 'Please wait...': 'Load issues'
                            }}</button>
                    </div>
                    <div class="table-responsive mt-3">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col" class="w-auto">ID</th>
                                    <th scope="col" class="w-25">Subject</th>
                                    <th scope="col" class="w-25">Category</th>
                                    <th scope="col" class="w-25">Status</th>
                                    <th scope="col" class="w-25 text-end">Updated</th>
                                </tr>
                            </thead>
                            <tbody class="text-truncate">
                                <tr v-if="issuesLoadingInProgress">
                                    <td colspan="5">
                                        <div class="loading"></div>
                                    </td>
                                </tr>
                                <tr v-else v-for="issue in issues.data" :key="issue.id">
                                    <th scope="row">{{ issue.id }}</th>
                                    <td>{{ issue.subject }}</td>
                                    <td>{{ issue.category.name }}</td>
                                    <td>{{ issue.status }}</td>
                                    <td class="text-end">{{ issue.updated_at }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </template>

</template>
