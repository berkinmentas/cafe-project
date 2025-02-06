import { createRouter, createWebHistory } from 'vue-router';
import Dashboard from './pages/Dashboard.vue';
import Login from './pages/auth/Login.vue';
import Register from './pages/auth/Register.vue';
import Products from './pages/Products.vue';
import Categories from './pages/Categories.vue';
import Profile from './pages/Profile.vue';
import DashboardLayout from './layouts/DashboardLayout.vue';
import NotFound from './pages/NotFound.vue';

const routes = [
    {
        path: '/admin/login',
        name: 'login',
        component: Login,
        meta: { requiresAuth: false }
    },
    {
        path: '/admin/register',
        name: 'register',
        component: Register,
        meta: { requiresAuth: false }
    },
    {
        path: '/admin',
        component: DashboardLayout,
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                name: 'dashboard',
                component: Dashboard
            },
            {
                path: 'products',
                name: 'products',
                component: () => import('./pages/products/index.vue')
            },
            {
                path: 'products/create',
                name: 'products.create',
                component: () => import('./pages/products/Create.vue')
            },
            {
                path: 'products/:id/edit',
                name: 'products.edit',
                component: () => import('./pages/products/Edit.vue')
            },
            {
                path: 'categories',
                name: 'categories',
                component: () => import('./pages/categories/index.vue')
            },
            {
                path: 'categories/create',
                name: 'categories.create',
                component: () => import('./pages/categories/Create.vue')
            },
            {
                path: 'categories/:id/edit',
                name: 'categories.edit',
                component: () => import('./pages/categories/Edit.vue')
            },
            {
                path: 'profile',
                name: 'profile',
                component: Profile
            }
        ]
    },
    {
        path: '/:pathMatch(.*)*',
        name: 'not-found',
        component: NotFound
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('access_token');
    const isAuthRoute = to.meta.requiresAuth === false;

    if (isAuthRoute && token) {
        next({ name: 'dashboard' });
        return;
    }

    if (!isAuthRoute && !token) {
        next({ name: 'login' });
        return;
    }

    next();
});

export default router;
