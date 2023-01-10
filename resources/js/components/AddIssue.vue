<script setup>

import { ref } from 'vue';
import { useGlobalStore } from '../stores/globalStore.js';
import { useRouter } from 'vue-router';
import InitializeError from './partials/InitializeError.vue';

const emit = defineEmits(['viewLoaded']);

const globalStore = useGlobalStore();
const router = useRouter();

const loadingInProgress = ref(true);
const errorOccured = ref(false);
const addingIssueInProgress = ref(false);
const categories = ref([]);
const category = ref(null);
const subject = ref("");
const description = ref("");

// Initialize the page

function initialize() {
    loadingInProgress.value = true;
    errorOccured.value = false;

    let axiosResponse = axios({
        method: 'GET',
        url: '/issues/initializeAddIssuePage',
        timeout: 30000,
    })
        .then((response) => {
            categories.value = response.data;
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

// Form validation

function formValidated() {

    let validationErrors = [];

    // Validate subject

    if (subject.value.length > 0 && subject.value.length <= 255) {
    } else {
        validationErrors.push('Subject is invalid');
    }

    // Validate category

    if (category.value > 0) {
    } else {
        validationErrors.push('Category is invalid');
    }

    // Validate description

    if (description.value.length > 0 && description.value.length <= 65535) {
    } else {
        validationErrors.push('Description is invalid');
    }

    if (validationErrors.length === 0) {
        return true;
    } else {
        globalStore.showToastMessage(validationErrors[0]);
        return false;
    }

}

// Add issue

async function addIssue() {

    if (addingIssueInProgress.value || !formValidated()) {
        return false;
    }

    addingIssueInProgress.value = true;

    let parameters = {

        subject: subject.value,
        category: category.value,
        description: description.value,
    };

    let axiosResponse = await axios({
        method: 'POST',
        url: '/issues/add',
        timeout: 30000,
        data: parameters,
    })
        .then((response) => {
            globalStore.setAlert('issues', 'success', 'Issue has been added.');
            router.push({
                name: 'issues',
                replace: true,
            });
        })
        .catch(function (error) {
            globalStore.handleError(error);
            addingIssueInProgress.value = false;
        })

}

initialize();

</script>

<template>
    <div v-if="loadingInProgress" class="loading">
    </div>
    <template v-else-if="!errorOccured">
        <div class="container">
            <div class="row justify-content-center mt-2 mt-sm-3">
                <div class="col-12">
                    <div class="row">
                        <div class="col-12">
                            <h5 class="text-secondary">Add issue
                            </h5>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <form>
                                <div class="row gy-3 gy-sm-4">
                                    <div class="col-12">
                                        <input type="text" class="form-control bg-primary-subtle" minlength="1"
                                            maxlength="255" id="email" placeholder="Subject" required
                                            v-model.trim="subject">
                                    </div>
                                    <div class="col-12">
                                        <select class="form-select bg-primary-subtle" id="category" v-model="category"
                                            required>
                                            <option value="null" selected disabled>Category</option>
                                            <option v-for="category in categories" :key="category.id"
                                                :value="category.id">{{ category.name }}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <textarea v-model="description" class="form-control bg-primary-subtle"
                                            minlength="1" id="description" maxlength="65535" rows="10"
                                            placeholder="Describe your problem..." required></textarea>
                                    </div>
                                    <div class="col-12">
                                        <button type="button" v-on:click="addIssue()" class="btn btn-success w-100"
                                            :disabled="addingIssueInProgress"
                                            v-text="addingIssueInProgress ? 'Please wait...' : 'Submit'"></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
    <InitializeError v-else v-on:reinitialize="initialize()" />
</template>
