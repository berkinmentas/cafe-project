<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50">
    <div class="text-center">
      <h1 class="text-6xl font-bold text-[#4F45E4]">404</h1>
      <p class="mt-4 text-xl text-gray-600">Sayfa bulunamadı</p>
      <router-link 
        :to="{ name: 'dashboard' }" 
        class="mt-6 inline-block px-6 py-3 bg-[#4F45E4] text-white rounded-lg hover:bg-[#4F45E4]/90 transition-colors"
      >
        Ana Sayfaya Dön
      </router-link>
    </div>
  </div>
</template> 