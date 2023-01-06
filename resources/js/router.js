import { createRouter, createWebHistory } from 'vue-router'
import Login from "./components/Login.vue";
import Index from "./components/Index.vue";
import Register from "./components/Register.vue";
import ResetPassword from "./components/ResetPassword.vue";
import ResetPasswordFinish from "./components/ResetPasswordFinish.vue";

const router = createRouter({
    scrollBehavior() {
        return {
            top: 0,
            behavior: 'smooth'
        }
    },
    history: createWebHistory(),
    routes: [
        {
            path: '/',
            name: 'index',
            component: Index,
            meta: { title: 'The Force Track' }
        },
        {
            path: '/login',
            name: 'login',
            component: Login,
            meta: { title: 'Log in' }
        },
        {
            path: '/register',
            name: 'register',
            component: Register,
            meta: { title: 'Create new account' }
        },
        {
            path: '/resetPassword',
            name: 'resetPassword',
            component: ResetPassword,
            meta: { title: 'Reset password' }
        },
        {
            path: '/resetPasswordFinish',
            name: 'resetPasswordFinish',
            component: ResetPasswordFinish,
            meta: { title: 'Set a new password' }
        },
    ],
});

router.beforeResolve(async (to) => {
    if (to.meta.title) {
        document.title = to.meta.title;
    }
});

export default router;