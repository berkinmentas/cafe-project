<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Giriş Yap
                </h2>
            </div>
            <form @submit.prevent="handleLogin" class="mt-8 space-y-6">
                <div class="rounded-md shadow-sm -space-y-px">
                    <div>
                        <label for="email" class="sr-only">Email</label>
                        <input
                            v-model="email"
                            id="email"
                            name="email"
                            type="email"
                            required
                            class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                            placeholder="Email adresi"
                        >
                    </div>
                    <div>
                        <label for="password" class="sr-only">Parola</label>
                        <input
                            v-model="password"
                            id="password"
                            name="password"
                            type="password"
                            required
                            class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
                            placeholder="Parola"
                        >
                    </div>
                </div>

                <div>
                    <button
                        type="submit"
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        Giriş Yap
                    </button>
                </div>
                <div class="text-center">
                    <router-link
                        :to="{ name: 'register' }"
                        class="font-medium text-indigo-600 hover:text-indigo-500"
                    >
                        Hesabınız yok mu? Kayıt olun
                    </router-link>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useAuthStore } from '../../stores/auth';
import { useRouter } from 'vue-router';
import Swal from 'sweetalert2';

const authStore = useAuthStore();
const router = useRouter();
const email = ref('');
const password = ref('');

const handleLogin = async () => {
    try {
        await authStore.login(email.value, password.value);
        Swal.fire({
            icon: 'success',
            title: 'Giriş Başarılı',
            text: 'Yönlendiriliyorsunuz...',
            showConfirmButton: false,
            timer: 1500
        });
        router.push({name: 'dashboard'});
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Giriş Başarısız',
            text: error.response?.data?.message || 'Bir hata oluştu',
            confirmButtonText: 'Tamam'
        });
    }
};
</script>
