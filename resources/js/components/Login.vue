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
                        Log in
                    </div>
                    <div class="card-body p-sm-4">
                        <div class="row gy-3 g-sm-4">
                            <div class="col-12">
                                <input type="email" class="form-control bg-body-secondary" minlength="1"
                                    maxlength="255" id="email" placeholder="E-mail address" required v-model.trim="email"
                                    v-on:keyup.enter="">
                            </div>
                            <div class="col-12">
                                <input type="password" class="form-control bg-body-secondary" minlength="8"
                                    maxlength="255" id="password" placeholder="Password" required v-model.trim="password"
                                    v-on:keyup.enter="">
                            </div>
                            <div class="col-12">
                                <button type="button" v-on:click="" class="btn btn-success w-100"
                                    v-bind:class="{ disabled: 1==2 }"
                                    v-text="1==2 ? 'Please wait...' : 'Continue'"></button>
                            </div>
                            <div class="col-12 text-end">
                                <router-link class="me-4" :to="{ name: 'index' }">Create new account</router-link>
                                <router-link :to="{ name: 'index' }">Reset password</router-link>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>
