import '../css/app.css';
import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';
import App from './App.vue';
import Dashboard from './pages/Dashboard.vue';
import Cafes from './pages/Cafes.vue';
import Products from "./pages/Products.vue";

const routes = [
    { path: '/', component: Dashboard },
    { path: '/cafes', component: Cafes },
    { path: '/products', component: Products },
];

const router = createRouter({
    history: createWebHistory('/admin'),
    routes,
});

const app = createApp(App);
app.use(router);
app.mount('#app');
