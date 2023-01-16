import { useRouter } from 'vue-router';
import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import { useGlobalStore } from './globalStore';

export const useUserStore = defineStore('userStore', () => {

    // Stores

    const globalStore = useGlobalStore();

    // Routes

    const router = useRouter();

    // State

    const user = ref(null);
    const email = ref('');
    const password = ref('');

    // Computed

    const emailReactive = computed(() => {
        return email;
    })

    const passwordReactive = computed(() => {
        return password;
    })

    // Methods

    // Set user login data shared between views

    function setLoginData(providedEmail, providedPassword) {
        email.value = providedEmail;
        password.value = providedPassword;
    }

    // Set logged user

    function setUser(providedUser) {
        user.value = providedUser;
    }

    // Redirect user to login page

    function redirectToLogin() {
        if (user.value) {
            setUser(null);
        }
        router.push({
            name: 'login',
        });
    }

    // Logout the user

    async function logoutUser() {

        let axiosResponse = await axios({
            method: 'GET',
            url: '/logout',
            timeout: 30000,
        })
            .then((response) => {
                redirectToLogin();
            })
            .catch(function (error) {
                globalStore.handleError(error);
            })

    }

    return {
        user,
        emailReactive,
        passwordReactive,
        setLoginData,
        setUser,
        redirectToLogin,
        logoutUser,
    }

})
