<script setup>

import { ref, watch } from 'vue';
import { useGlobalStore } from '../stores/globalStore.js';
import { useUserStore } from '../stores/userStore.js';
import { useRouter } from 'vue-router';

const props = defineProps({
    issueId: [String, Number],
});

const emit = defineEmits(['viewLoaded']);

const globalStore = useGlobalStore();
const userStore = useUserStore();
const router = useRouter();

const loadingInProgress = ref(true);
const updatingIssueInProgress = ref(false);
const issue = ref(null);
const selectedStatus = ref(null);
const statuses = ref([]);

// Initialize the page

function initialize() {
    loadingInProgress.value = true;
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
    <template v-else>
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
                    </div>
                </div>
            </div>
        </div>
    </template>

</template>
