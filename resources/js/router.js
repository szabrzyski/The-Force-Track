import { createRouter, createWebHistory } from 'vue-router'
import Login from "./components/Login.vue";

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
            path: '/login',
            name: 'login',
            component: Login,
            meta: { title: 'Log in'}
        },
    ],
});

router.beforeResolve(async (to) => {
    if (to.meta.title) {
        document.title = to.meta.title;
    }
});

export default router;