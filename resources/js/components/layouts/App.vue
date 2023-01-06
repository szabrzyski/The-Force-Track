<script setup>
import Header from './Header.vue'
import Footer from './Footer.vue';
import ToastMessage from '../partials/ToastMessage.vue';
</script>

<template>
    <div class="d-flex flex-column min-vh-100">
        <Header />
        <RouterView />
        <ToastMessage />
        <Footer />
    </div>
</template>
