<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Sidebar -->
        <aside class="fixed inset-y-0 left-0 w-64 bg-white shadow-lg">
            <div class="flex items-center justify-center h-16 border-b">
                <h1 class="text-xl font-bold text-[#4F45E4]">Admin Panel</h1>
            </div>
            
            <nav class="p-4 space-y-2">
                <router-link 
                    v-for="item in menuItems" 
                    :key="item.path"
                    :to="item.path"
                    class="flex items-center px-4 py-2 text-gray-700 rounded-lg hover:bg-gray-100"
                    :class="{ 'bg-[#4F45E4]/10 text-[#4F45E4]': isActive(item.path) }"
                >
                    <component :is="item.icon" class="w-5 h-5 mr-3" />
                    {{ item.name }}
                </router-link>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="pl-64">
            <!-- Header -->
            <header class="h-16 bg-white shadow-sm">
                <div class="flex items-center justify-between px-6 h-full">
                    <h2 class="text-lg font-medium text-gray-800">{{ currentPageTitle }}</h2>
                    
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-600">{{ user?.name }}</span>
                        <button 
                            @click="handleLogout"
                            class="px-4 py-2 text-sm text-red-600 rounded-lg hover:bg-red-50"
                        >
                            Çıkış Yap
                        </button>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="p-6">
                <router-view />
            </div>
        </main>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useAuthStore } from '../stores/auth';
import Swal from 'sweetalert2';

const router = useRouter();
const route = useRoute();
const authStore = useAuthStore();

const menuItems = [
    { path: '/admin', name: 'Dashboard', icon: 'HomeIcon' },
    { path: '/admin/products', name: 'Products', icon: 'ProductIcon' },
    { path: '/admin/categories', name: 'Categories', icon: 'CategoryIcon' }
];

const currentPageTitle = computed(() => {
    return menuItems.find(item => item.path === route.path)?.name || 'Dashboard';
});

const logout = async () => {
    try {
        await authStore.logout();
        router.push('/admin/login');
        Swal.fire({
            icon: 'success',
            title: 'Logged out successfully',
            showConfirmButton: false,
            timer: 1500
        });
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Logout failed',
            text: 'Please try again'
        });
    }
};

const isActive = (path) => {
    return route.path === path;
};

const handleLogout = () => {
    logout();
};
</script>
