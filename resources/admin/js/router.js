import { createRouter, createWebHistory } from 'vue-router';
import Dashboard from './pages/Dashboard.vue';
import Categories from './pages/Categories.vue';
import Products from './pages/Products.vue';

const routes = [
    { path: '/', component: Dashboard },
    { path: '/categories', component: Categories },
    { path: '/products', component: Products },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
