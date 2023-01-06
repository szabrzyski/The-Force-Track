import { createRouter, createWebHistory } from 'vue-router'
import Login from "./components/Login.vue";
import Index from "./components/Index.vue";

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
            meta: { title: 'The Force Track'}
        },
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