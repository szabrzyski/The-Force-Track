<script setup>

import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useGlobalStore } from '../stores/globalStore.js';
import { useUserStore } from '../stores/userStore.js';
import Alert from './partials/Alert.vue';

const emit = defineEmits(['viewLoaded']);

const email = ref("");
const password = ref("");
const registrationInProgress = ref(false);

const globalStore = useGlobalStore();
const userStore = useUserStore();
const router = useRouter();

//Form validation

function formValidated() {

    let validationErrors = [];

    // Validate e-mail

    if (email.value.length > 0 && email.value.length <= 255 && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
    } else {
        validationErrors.push('Correct the e-mail address');
    }

    // Validate password

    if (password.value.length >= 8 && password.value.length <= 255) {
    } else {
        validationErrors.push('Correct the password (min. 8 characters)');
    }

    if (validationErrors.length === 0) {
        return true;
    } else {
        globalStore.showToastMessage(validationErrors[0]);
        return false;
    }

}

// Create account

async function createAccount() {

    if (registrationInProgress.value || !formValidated()) {
        return false;
    }

    registrationInProgress.value = true;

    let parameters = {

        email: email.value,
        password: password.value,
    };

    let axiosResponse = await axios({
        method: 'POST',
        url: '/createAccount',
        timeout: 30000,
        data: parameters,
    })
        .then((response) => {
            userStore.setLoginData(email.value, password.value);
            globalStore.setAlert('login', 'success', 'Check your e-mail to activate your account.');
            router.push({
                name: 'login',
                replace: true,
            });
        })
        .catch(function (error) {
            globalStore.handleError(error);
            registrationInProgress.value = false;
        })

}

emit('viewLoaded');

</script>

<template>
    <div class="container">
        <div class="row justify-content-center mt-2 mt-sm-3">
            <Alert columns='col-12 col-md-10 col-lg-8 col-xl-7 col-xxl-6' margins='px-md-3 px-xl-4' />
            <div class="col-12 col-md-10 col-lg-8 col-xl-7 col-xxl-6 mt-2 mt-sm-1 mt-md-2 mt-lg-4">
                <div class="card">
                    <div class="card-header text-center text-bg-secondary py-2">
                        Create account
                    </div>
                    <div class="card-body p-sm-4">
                        <div class="row gy-3 g-sm-4">
                            <div class="col-12">
                                <input type="email" v-model.trim="email" class="form-control bg-primary-subtle"
                                    name="email" id="email" placeholder="E-mail address">
                            </div>
                            <div class="col-12">
                                <input type="password" v-model="password" class="form-control bg-primary-subtle"
                                    name="password" id="haslo" placeholder="Password (min. 8 characters)" minlength="8"
                                    maxlength="255">
                            </div>
                            <div class="col-12">
                                <button type="button" v-on:click="createAccount()" class="btn btn-success w-100"
                                    :disabled="registrationInProgress"
                                    v-text="registrationInProgress ? 'Please wait...' : 'Continue'"></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
