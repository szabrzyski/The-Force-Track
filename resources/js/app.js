import { createApp } from 'vue';
import { createPinia } from 'pinia';
import router from "./router.js";
import App from "./components/layouts/App.vue";
import * as bootstrap from 'bootstrap';
import axios from 'axios';

// Global data

window.bootstrap = bootstrap;
window.axios = axios;

// Data

const pinia = createPinia();
const app = createApp(App);

// Initialize app

app.use(pinia).use(router).mount("#app");