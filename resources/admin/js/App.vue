<script setup>
import { RouterView } from 'vue-router';
</script>

<template>
    <div>
        <nav class="bg-blue-600 text-white p-4 bg-blue-y">
            <h1 class="text-lg">Kafe Yönetim Paneli</h1>
        </nav>

        <main class="p-4">
            <RouterView />
        </main>
    </div>
</template>

<style scoped>
</style>
