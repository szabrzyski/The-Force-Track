<script setup>

import Alert from './partials/Alert.vue';
const emit = defineEmits(['viewLoaded']);

emit('viewLoaded');

</script>

<template>
    <div class="container">
        <div class="row justify-content-center mt-2 mt-sm-3">
            <Alert />
            <div class="col-12 mt-lg-3">
                Index page
            </div>
        </div>
    </div>
</template>
