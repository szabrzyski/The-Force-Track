<script setup>

import { useRouter } from 'vue-router';
import { ref } from 'vue';
import Alert from './partials/Alert.vue';
import { useGlobalStore } from '../stores/globalStore.js';
import { useUserStore } from '../stores/userStore.js';

const emit = defineEmits(['viewLoaded']);

const globalStore = useGlobalStore();
const userStore = useUserStore();
const router = useRouter();

const email = ref("");
const passwordResetInProgress = ref(false);

// Send the password reset link

async function resetPassword() {

    if (passwordResetInProgress.value || !formValidated()) {
        return false;
    }

    passwordResetInProgress.value = true;

    let axiosResponse = await axios({
        method: 'POST',
        url: '/resetPassword',
        timeout: 30000,
        data: {
            email: email.value,
        },
    })
        .then((response) => {
            userStore.setLoginData(email.value, '');
            globalStore.setAlert('login', 'success', 'Password reset instruction has been sent to your e-mail address.');
            router.push({
                name: 'login',
                replace: true,
            });
        })
        .catch(function (error) {
            globalStore.handleError(error);
            passwordResetInProgress.value = false;
        })

}

// Form validation

function formValidated() {

    let validationErrors = [];

    if (email.value.length > 0 && email.value.length <= 255 && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
    } else {
        validationErrors.push('Invalid e-mail address');
    }

    if (validationErrors.length === 0) {
        passwordResetInProgress.value = true;
        return true;
    } else {
        globalStore.showToastMessage(validationErrors[0]);
        return false;
    }

}

emit('viewLoaded');

</script>

<template>
    <div class="container">
        <div class="row justify-content-center mt-2 mt-sm-3">
            <Alert columns='col-12 col-md-10 col-lg-8 col-xl-7 col-xxl-6' margins='px-md-3 px-xl-4' />
            <div class="col-12 col-md-10 col-lg-8 col-xl-7 col-xxl-6 mt-2 mt-sm-1 mt-md-2 mt-lg-4">
                <div class="card">
                    <form v-on:keyup.enter="resetPassword()">
                        <div class="card-header text-center text-bg-secondary py-2">
                            Reset password
                        </div>
                        <div class="card-body p-sm-4">
                            <div class="row gy-3 g-sm-4">
                                <div class="col-12">
                                    <input type="email" class="form-control bg-primary-subtle" minlength="1"
                                        maxlength="255" id="email" placeholder="E-mail address" required
                                        v-model.trim="email">
                                </div>
                                <div class="col-12">
                                    <button type="button" v-on:click="resetPassword()" class="btn btn-success w-100"
                                        :disabled="passwordResetInProgress" v-text="passwordResetInProgress ? 'Please wait...' :
                                        'Submit'"></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
