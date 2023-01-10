<script setup>
import { ref, computed } from 'vue';
import Header from './Header.vue'
import Footer from './Footer.vue';
import ToastMessage from '../partials/ToastMessage.vue';
import { useGlobalStore } from '../../stores/globalStore.js';

const globalStore = useGlobalStore();
const viewIsLoaded = ref(false);

const showApp = computed(() => {
    return !globalStore.loadingInProgress && (globalStore.errorOccured || viewIsLoaded.value);
})

</script>

<template>
    <div v-if="!showApp" class="app-loading">
    </div>
    <template v-if="!globalStore.loadingInProgress">
        <div :class="[showApp ? 'd-flex flex-column min-vh-100' : 'd-none']">
            <Header />
            <RouterView v-on:viewLoaded="viewIsLoaded = true" />
            <ToastMessage />
            <Footer />
        </div>
    </template>
</template>
