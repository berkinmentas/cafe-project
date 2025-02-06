import { defineStore } from 'pinia'
import axios from 'axios'

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null,
        token: localStorage.getItem('access_token'),
        isAuthenticated: false
    }),
    actions: {
        async login(email, password) {
            try {
                const response = await axios.post('/api/admin/login', {
                    email,
                    password
                });

                const { access_token } = response.data.data;

                this.token = access_token;
                localStorage.setItem('access_token', access_token);
                axios.defaults.headers.common['Authorization'] = `Bearer ${access_token}`;

                this.isAuthenticated = true;

                return response;
            } catch (error) {
                throw error;
            }
        },

        async logout() {
            try {
                await axios.post('/api/admin/logout');

                this.token = null;
                this.user = null;
                this.isAuthenticated = false;

                localStorage.removeItem('access_token');
                delete axios.defaults.headers.common['Authorization'];

            } catch (error) {
                console.error('Logout error:', error);
                throw error;
            }
        },

        async register(userData) {
            try {
                const response = await axios.post('/api/admin/register', userData);

                const { access_token } = response.data.data;

                this.token = access_token;
                localStorage.setItem('access_token', access_token);
                axios.defaults.headers.common['Authorization'] = `Bearer ${access_token}`;

                this.isAuthenticated = true;

                return response;
            } catch (error) {
                throw error;
            }
        },

        async checkAuth() {
            if (this.token) {
                this.isAuthenticated = true;
                axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
            }
        }
    },

    getters: {
        getUser: (state) => state.user,
        isLoggedIn: (state) => state.isAuthenticated
    }
})

