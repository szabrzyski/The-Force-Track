<script setup>

import { useGlobalStore } from '../../stores/globalStore.js';
import { computed } from 'vue';
import { useRoute, onBeforeRouteLeave } from 'vue-router';

const globalStore = useGlobalStore();

const props = defineProps({
    margins: String,
    columns: String,
    alert: Object,
});

const pageMatch = computed(() => {
    return globalStore.alert.page === route.name;
})

const route = useRoute();

const showAlert = computed(() => {
    return props.alert || pageMatch.value;
})

const alertType = computed(() => {
    return props.alert ? props.alert.type : pageMatch.value ? globalStore.alert.type : null;
})

const alertMessage = computed(() => {
    return props.alert ? props.alert.message : pageMatch.value ? globalStore.alert.message : null;
})

onBeforeRouteLeave((to, from) => {
    if (globalStore.alert.page === from.name) {
        globalStore.resetAlert();
    }
})

</script>

<template>
    <div v-if="showAlert" class="col-12" :class="margins">
        <div class="alert alert-dismissible text-break mx-auto my-2 mb-sm-3 mb-lg-0"
            :class="[columns ?? 'col-12', alertType === 'success' ? 'alert-success' : alertType === 'error' ? 'alert-danger' : alertType === 'info' ? 'alert-primary' : '']"
            role="alert">
            <span> {{ alertMessage }} </span>
            <button type="button" class="btn-close h-100 py-0" data-bs-dismiss="alert"></button>
        </div>
    </div>
</template>