<script setup>

// Emits

const emit = defineEmits(['reinitialize']);

</script>

<template>
    <div class="container my-auto">
        <div class="row justify-content-center gy-3">
            <div class="col-12 text-center text-danger">
                <i class="fa-solid fa-2xl fa-bug"></i>
            </div>
            <div class="col-12 text-center">
                An error occured while loading the page
            </div>
            <div class="col-11 col-sm-8 col-md-6 col-lg-4 col-xxl-3 d-flex justify-content-center">
                <button type="button" class="btn btn-success w-100" v-on:click="emit('reinitialize')">Try
                    again</button>
            </div>
        </div>
    </div>
</template>

