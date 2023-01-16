<script setup>

import { ref, watch } from 'vue';
import { useGlobalStore } from '../stores/globalStore.js';
import { useUserStore } from '../stores/userStore.js';
import InitializeError from './partials/InitializeError.vue';

// Props

const props = defineProps({
    issueId: [String, Number],
});

// Emits

const emit = defineEmits(['viewLoaded']);

// Stores

const globalStore = useGlobalStore();
const userStore = useUserStore();

// Data

const loadingInProgress = ref(true);
const errorOccured = ref(false);
const updatingIssueInProgress = ref(false);
const issue = ref(null);
const selectedStatus = ref(null);
const statuses = ref([]);
const comment = ref("");

// Methods

// Initialize the page

function initialize() {

    loadingInProgress.value = true;
    errorOccured.value = false;
    let axiosResponse = axios({
        method: 'GET',
        url: '/issue/' + props.issueId + '/initializeIssueDetailsPage',
        timeout: 30000,
    })
        .then((response) => {
            issue.value = response.data.issue;
            selectedStatus.value = issue.value.status.id;
            statuses.value = response.data.statuses;
        })
        .catch(function (error) {
            globalStore.handleError(error);
            errorOccured.value = true;
        })
        .then(function () {
            loadingInProgress.value = false;
            emit('viewLoaded');
        });

}

// Update issue status

async function updateStatus(oldStatus) {

    if (updatingIssueInProgress.value || !selectedStatus.value > 0) {
        return false;
    }

    updatingIssueInProgress.value = true;

    let parameters = {
        newStatus: selectedStatus.value,
    };

    let axiosResponse = await axios({
        method: 'PATCH',
        url: '/issue/' + issue.value.id + '/updateStatus',
        timeout: 30000,
        data: parameters,
    })
        .then((response) => {
            updatingIssueInProgress.value = false;
            globalStore.showToastMessage('Status has been updated');
        })
        .catch(function (error) {
            selectedStatus.value = oldStatus;
            globalStore.handleError(error);
        })

}

// Add comment

async function addComment() {

    if (updatingIssueInProgress.value || !comment.value.length > 0) {
        return false;
    }

    updatingIssueInProgress.value = true;

    let parameters = {
        comment: comment.value,
    };

    let axiosResponse = await axios({
        method: 'POST',
        url: '/issue/' + issue.value.id + '/addComment',
        timeout: 30000,
        data: parameters,
    })
        .then((response) => {
            updatingIssueInProgress.value = false;
            issue.value.comments.push(response.data);
            comment.value = '';
            globalStore.showToastMessage('Comment added');
        })
        .catch(function (error) {
            globalStore.handleError(error);
        })

}

// Watch for new selected status

watch(selectedStatus, async (newStatus, oldStatus) => {
    if (!loadingInProgress.value) {
        if (updatingIssueInProgress.value) {
            updatingIssueInProgress.value = false;
        } else {
            updateStatus(oldStatus);
        }
    }

})

initialize();

</script>

<template>
    <div v-if="loadingInProgress" class="loading">
    </div>
    <template v-else-if="!errorOccured">
        <div class="container">
            <div class="row justify-content-center mt-2 mt-sm-3">
                <div class="col-12">
                    <div class="row gy-2">
                        <div class="col-12">
                            <h5 class="text-secondary">Issue details
                            </h5>
                        </div>
                        <div class="col-12">
                            <h6><span class="text-secondary me-2">Status:</span> <select
                                    class="form-select form-select-sm d-inline w-auto bg-primary-subtle"
                                    id="selectedStatus" :disabled="!userStore.user?.admin || updatingIssueInProgress"
                                    v-model="selectedStatus" required>
                                    <option v-for="status in statuses" :key="status.id" :value="status.id">{{
                                        status.name
                                    }}
                                    </option>
                                </select>
                            </h6>
                        </div>
                        <div class="col-12">
                            <h6><span class="text-secondary">Category:</span> {{ issue.category.name }}
                            </h6>
                        </div>
                        <div class="col-12">
                            <h6 class="text-truncate"><span class="text-secondary">Subject:</span> {{ issue.subject }}
                            </h6>
                        </div>
                        <div class="col-12 pre-line max-h-500 overflow-auto">
                            <h6><span class="text-secondary">Description:</span> {{ issue.description }}
                            </h6>
                        </div>
                        <div class="col-12">
                            <h5 class="text-secondary">Comments</h5>
                        </div>
                        <div v-if="issue.comments.length" class="col-12 overflow-auto max-h-500">
                            <div v-for="(comment, index) in issue.comments" :key="comment.id" class="row"
                                :class="{ 'mb-3': index < issue.comments.length - 1 }">
                                <div class="col-12 text-truncate text-secondary">
                                    {{ comment.user.email }} on {{ comment.created_at }}
                                </div>
                                <div class="col-12 pre-line">
                                    {{ comment.comment }}
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mt-3">
                            <textarea v-model="comment" class="form-control bg-primary-subtle" minlength="1"
                                id="comment" maxlength="65535" rows="8" placeholder="Add comment..."
                                required></textarea>
                        </div>
                        <div class="col-12 mt-3">
                            <button type="button" v-on:click="addComment()" class="btn btn-success w-100"
                                :disabled="updatingIssueInProgress || !comment.length"
                                v-text="updatingIssueInProgress ? 'Please wait...' : 'Submit'"></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
    <InitializeError v-else v-on:reinitialize="initialize()" />
</template>
