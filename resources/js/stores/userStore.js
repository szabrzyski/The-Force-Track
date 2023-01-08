import { useRouter } from 'vue-router';
import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import { useGlobalStore } from './globalStore';

export const useUserStore = defineStore('userStore', () => {

    const user = ref(null);
    const email = ref('');
    const password = ref('');
    const router = useRouter();
    const globalStore = useGlobalStore();

    const emailReactive = computed(() => {
        return email;
    })

    const passwordReactive = computed(() => {
        return password;
    })

    function setLoginData(providedEmail, providedPassword) {
        email.value = providedEmail;
        password.value = providedPassword;
    }

    function setUser(providedUser) {
        user.value = providedUser;
    }

    function redirectToLogin() {
        if (user.value) {
            setUser(null);
        }
        router.push({
            name: 'login',
        });
    }

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
