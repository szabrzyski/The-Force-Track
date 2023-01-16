<script setup>

import { ref } from 'vue';
import Alert from './partials/Alert.vue';
import { useGlobalStore } from '../stores/globalStore.js';
import { useUserStore } from '../stores/userStore.js';
import { useRouter, useRoute } from 'vue-router';

// Emits

const emit = defineEmits(['viewLoaded']);

// Stores

const globalStore = useGlobalStore();
const userStore = useUserStore();

// Routing

const router = useRouter();
const route = useRoute();

// Data

const loginInProgress = ref(false);
const email = userStore.emailReactive;
const password = userStore.passwordReactive;

// Methods

// Initialize the page

function initialize() {

    let activatedAccountEmail = route.query.email;
    if (activatedAccountEmail) {
        email.value = activatedAccountEmail;
    }

    emit('viewLoaded');

}

// Login the user

async function loginUser() {

    if (loginInProgress.value || !formValidated()) {
        return false;
    }

    loginInProgress.value = true;

    let axiosResponse = await axios({
        method: 'POST',
        url: '/login',
        timeout: 30000,
        data: {
            email: email.value,
            password: password.value,
        },
    })
        .then((response) => {
            if (response.headers['content-type'].includes('application/json')) {
                let user = response.data.user;
                let redirectTo = response.data.redirectTo;
                let queryRedirect = route.query.redirect;
                var redirect = queryRedirect ?? redirectTo ?? router.resolve({
                    name: 'issues',
                }).path;
                userStore.setUser(user);
            }
            router.push({
                path: redirect,
                replace: true
            });

        })
        .catch(function (error) {
            globalStore.handleError(error);
            loginInProgress.value = false;
        })

}

// Form validation

function formValidated() {

    let validationErrors = [];

    // Validate e-mail

    if (email.value.length > 0 && email.value.length <= 255 && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
    } else {
        validationErrors.push('E-mail is invalid');
    }

    // Validate password

    if (password.value.length >= 8 && password.value.length <= 255) {
    } else {
        validationErrors.push('Password is invalid');
    }

    if (validationErrors.length === 0) {
        return true;
    } else {
        globalStore.showToastMessage(validationErrors[0]);
        return false;
    }
}

initialize();

</script>

<template>
    <div class="container">
        <div class="row justify-content-center mt-2 mt-sm-3">
            <Alert columns='col-12 col-md-10 col-lg-8 col-xl-7 col-xxl-6' margins='px-md-3 px-xl-4' />
            <div class="col-12 col-md-10 col-lg-8 col-xl-7 col-xxl-6 mt-2 mt-sm-1 mt-md-2 mt-lg-4">
                <div class="card">
                    <form v-on:keyup.enter="loginUser()">
                        <div class="card-header text-center text-bg-secondary py-2">
                            Log in
                        </div>
                        <div class="card-body p-sm-4">
                            <div class="row gy-3 g-sm-4">
                                <div class="col-12">
                                    <input type="email" class="form-control bg-primary-subtle" minlength="1"
                                        maxlength="255" id="email" placeholder="E-mail address" required
                                        v-model.trim="email">
                                </div>
                                <div class="col-12">
                                    <input type="password" class="form-control bg-primary-subtle" minlength="8"
                                        maxlength="255" id="password" placeholder="Password" required
                                        v-model.trim="password">
                                </div>
                                <div class="col-12">
                                    <button type="button" v-on:click="loginUser()" class="btn btn-success w-100"
                                        v-bind:class="{ disabled: loginInProgress }"
                                        v-text="loginInProgress ? 'Please wait...' : 'Submit'"></button>
                                </div>
                                <div class="col-12 text-end">
                                    <router-link class="me-4" :to="{ name: 'register' }">Create new
                                        account</router-link>
                                    <router-link :to="{ name: 'resetPassword' }">Reset password</router-link>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
