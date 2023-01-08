
import { ref } from 'vue';
import { defineStore } from 'pinia';
import { useUserStore } from './userStore.js';

export const useGlobalStore = defineStore('globalStore', () => {

    const loadingInProgress = ref(true);
    const errorOccured = ref(false);
    const toastMessageElement = ref(null);
    const toastMessage = ref("");
    const alert = ref({
        page: null,
        type: null,
        message: null
    });
    const userStore = useUserStore();

    // Set toast message element

    function setToastMessageElement(providedToastMessageElement) {
        toastMessageElement.value = providedToastMessageElement;
    }

    // Show toast message

    function showToastMessage(message, timeout = null) {
        if (!timeout) {
            timeout = 5000;
        }
        toastMessage.value = message;
        let toastMessageElementBootstrap = bootstrap.Toast.getOrCreateInstance(toastMessageElement.value, { delay: timeout });
        toastMessageElementBootstrap.show();
    }

    // Set alert message

    function setAlert(page, type, message) {
        alert.value.page = page;
        alert.value.type = type;
        alert.value.message = message;
    }

    // Reset alert message

    function resetAlert() {
        alert.value = {
            page: null,
            type: null,
            message: null
        }
    }

    // Initialize application

    async function initializeApp() {

        loadingInProgress.value = true;
        errorOccured.value = false;

        let axiosResponse = await axios({
            method: 'GET',
            url: '/initialize',
            timeout: 30000,
        })
            .then((response) => {
                let sessionAlert = response.data.alert;
                if (sessionAlert) {
                    alert.value = JSON.parse(sessionAlert);
                }
                userStore.setUser(response.data.user);
            })
            .catch(function (error) {
                errorOccured.value = true;
            })
            .then(function () {
                loadingInProgress.value = false;
                return errorOccured.value == false;
            });
    }

    // Handle errors

    function handleError(error) {
        let message = '';
        if (error.code == 'ECONNABORTED') {
            return false;
        }
        else if (error.response) {
            let errorCode = error.response.status;
            switch (errorCode) {
                case 0:
                    message = 'No response from the server';
                    break;
                case 401:
                    userStore.redirectToLogin();
                    break;
                case 403:
                    message = 'Unauthorized';
                    break;
                case 404:
                    message = 'Page not found';
                    break;
                case 419:
                    message = 'Session is expired';
                    break;
                case 420:
                    message = error.response.data;
                    break;
                case 427:
                    message = error.response.data[Object.keys(error.response.data)[0]][0];
                    break;
                case 429:
                    message = 'Request limit exceeded';
                    break;
                case 430:
                    return false;
                case 500:
                case 502:
                    message = 'Server error occured';
                    break;
                case 503:
                    message = 'Website is not available';
                    break;
                case 504:
                    message = 'Response timeout exceeded';
                    break;
                default:
                    message = 'An unexpected error occured';
                    console.log('Status code:', errorCode);
                    console.log('Data:', error.response.data);
                    console.log('Headers:', error.response.headers)
                    console.log('Config:', error.config);
            }

        } else if (error.request) {
            message = 'No response from the server';
            console.log('No response from the server:', error.request);
            console.log('Config:', error.config);
        } else {
            message = 'An unexpected error occured';
            console.log('Error message', error.message);
            console.log('Config', error.config);

        }

        if (message) {
            showToastMessage(message);
        }
    }

    return {
        loadingInProgress,
        errorOccured,
        toastMessageElement,
        toastMessage,
        alert,
        setToastMessageElement,
        showToastMessage,
        setAlert,
        resetAlert,
        initializeApp,
        handleError,
    }

})
