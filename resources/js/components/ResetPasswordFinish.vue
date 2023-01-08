<script setup>

import { useRouter, useRoute } from 'vue-router';
import Alert from './partials/Alert.vue';
import { ref } from 'vue';
import { useGlobalStore } from '../stores/globalStore.js';
import { useUserStore } from '../stores/userStore.js';

const emit = defineEmits(['viewLoaded']);

const props = defineProps({
    verificationCode: String,
});

const globalStore = useGlobalStore();
const userStore = useUserStore();

const router = useRouter();
const route = useRoute();

const password = ref('');
const passwordResetInProgress = ref(false);

async function finishPasswordReset() {

    if (passwordResetInProgress.value || !formValidated()) {
        return false;
    }

    passwordResetInProgress.value = true;

    let axiosResponse = await axios({
        method: 'PATCH',
        url: '/resetPasswordFinish',
        timeout: 30000,
        data: {
            password: password.value,
            verificationCode: props.verificationCode
        },
    })
        .then((response) => {
            userStore.setLoginData(route.query.email, password.value);
            globalStore.setAlert('login', 'success', 'Your password has been updated.');
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

function formValidated() {

    let validationErrors = [];

    if (password.value.length >= 8 && password.value.length <= 255) {
    } else {
        validationErrors.push('Invalid password');
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
                    <div class="card-header text-center text-bg-secondary py-2">
                        Set a new password
                    </div>
                    <div class="card-body p-sm-4">
                        <div class="row gy-3 g-sm-4">
                            <div class="col-12">
                                <input type="password" class="form-control bg-primary-subtle" minlength="8"
                                    maxlength="255" id="password" placeholder="Password" required
                                    v-model.trim="password" v-on:keyup.enter="finishPasswordReset()">
                            </div>
                            <div class="col-12">
                                <button type="button" v-on:click="finishPasswordReset()" class="btn btn-success w-100"
                                    :disabled="passwordResetInProgress"
                                    v-text="passwordResetInProgress ? 'Please wait...' : 'Continue'"></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
