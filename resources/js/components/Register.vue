<script setup>

import { ref } from 'vue';

const email = ref("");
const password = ref("");

</script>

<template>
    <div class="container">
        <div class="row justify-content-center mt-2 mt-sm-3">
            <div class="col-12 col-md-10 col-lg-8 col-xl-7 col-xxl-6 mt-2 mt-sm-1 mt-md-2 mt-lg-4">
                <div class="card">
                    <div class="card-header text-center text-bg-secondary py-2">
                        Create account
                    </div>
                    <div class="card-body p-sm-4">
                        <div class="row gy-3 g-sm-4">
                            <div class="col-12">
                                <input type="email" v-model.trim="email" class="form-control bg-body-secondary"
                                    name="email" id="email" placeholder="E-mail address">
                            </div>
                            <div class="col-12">
                                <input type="password" v-model="password" class="form-control bg-body-secondary"
                                    name="password" id="haslo" placeholder="Password (min. 8 characters)" minlength="8"
                                    maxlength="255">
                            </div>
                            <div class="col-12">
                                <button type="button" v-on:click="" class="btn btn-success w-100" :disabled="1 == 2"
                                    v-text="1 == 2 ? 'Please wait...' : 'Continue'"></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
