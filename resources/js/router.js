import { useGlobalStore } from './stores/globalStore';
import { useUserStore } from './stores/userStore';
import { createRouter, createWebHistory } from 'vue-router'
import Login from "./components/Login.vue";
import Issues from "./components/Issues.vue";
import AddIssue from "./components/AddIssue.vue";
import ShowIssue from "./components/ShowIssue.vue";
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
            name: 'issues',
            component: Issues,
            meta: { title: 'The Force Track', onlyLoggedUser: true }
        },
        {
            path: '/issue/:issueId',
            name: 'showIssue',
            component: ShowIssue,
            props: true,
            meta: { title: 'Issue details', onlyLoggedUser: true }
        },
        {
            path: '/issues/add',
            name: 'addIssue',
            component: AddIssue,
            meta: { title: 'Add issue', onlyLoggedUser: true }
        },
        {
            path: '/login',
            name: 'login',
            component: Login,
            meta: { title: 'Log in', onlyGuest: true }
        },
        {
            path: '/register',
            name: 'register',
            component: Register,
            meta: { title: 'Create new account', onlyGuest: true }
        },
        {
            path: '/resetPassword',
            name: 'resetPassword',
            component: ResetPassword,
            meta: { title: 'Reset password', onlyGuest: true }
        },
        {
            path: '/setNewPassword/:verificationCode',
            name: 'setNewPassword',
            component: ResetPasswordFinish,
            props: true,
            meta: { title: 'Set a new password', onlyGuest: true }
        },
        {
            path: '/:pathMatch(.*)*',
            name: 'notFound',
            redirect: { name: 'issues' }
        },
    ],
});

router.beforeEach(async (to) => {

    const globalStore = useGlobalStore();
    const userStore = useUserStore();

    if (globalStore.loadingInProgress) {
        await globalStore.initializeApp();
    }

    let loggedUser = userStore.user;
    let loggedUserIsAdmin = loggedUser ? loggedUser.admin : false;

    if (to.meta.onlyLoggedUser && !loggedUser) {
        return {
            name: 'login',
            query: { redirect: to.fullPath },
        }
    } else if (to.meta.onlyGuest && loggedUser) {
        return {
            name: 'issues',
        }
    } else if (to.meta.onlyAdmin && !loggedUserIsAdmin) {
        return {
            name: 'issues',
        }
    }
})

router.beforeResolve(async (to) => {
    if (to.meta.title) {
        document.title = to.meta.title;
    }
});

export default router;