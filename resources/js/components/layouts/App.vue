<script setup>
import { ref, computed } from 'vue';
import Header from './Header.vue'
import Footer from './Footer.vue';
import ToastMessage from '../partials/ToastMessage.vue';
import InitializeError from '../partials/InitializeError.vue';
import { useGlobalStore } from '../../stores/globalStore.js';

// Stores

const globalStore = useGlobalStore();

// Data

const viewIsLoaded = ref(false);

// Computed

const showApp = computed(() => {
    return !globalStore.loadingInProgress && (globalStore.errorOccured || viewIsLoaded.value);
})

</script>

<template>
    <div v-if="!showApp" class="app-loading">
    </div>
    <template v-if="!globalStore.loadingInProgress">
        <div :class="[showApp ? 'd-flex flex-column min-vh-100' : 'd-none']">
            <InitializeError v-if="globalStore.errorOccured" v-on:reinitialize="globalStore.initializeApp()" />
            <template v-else>
                <Header />
                <RouterView v-on:viewLoaded="viewIsLoaded = true" />
                <ToastMessage />
                <Footer />
            </template>
        </div>
    </template>
</template>
