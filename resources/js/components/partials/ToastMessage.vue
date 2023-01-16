<script setup>

import { ref, onMounted } from 'vue'
import { useGlobalStore } from '../../stores/globalStore.js';

// Stores

const globalStore = useGlobalStore();

// HTML references

const toastMessageElement = ref(null);

// Lifecycle

onMounted(() => {
    globalStore.setToastMessageElement(toastMessageElement.value);
})

</script>

<template>
    <div class="toast-container position-fixed bottom-0 end-0 p-2 p-sm-4">
        <div ref="toastMessageElement" class="toast hide opacity-100">
            <div class="toast-header">
                <i class="fas fa-info-circle"></i>
                <strong class="me-auto ms-2">Message</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
            </div>
            <div data-bs-dismiss="toast" class="toast-body opacity-100 lapka">
                {{ globalStore.toastMessage }}
            </div>
        </div>
    </div>
</template>