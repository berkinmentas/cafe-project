<template>
  <div class="space-y-6">
    <!-- Üst Başlık ve Aksiyon Alanı -->
    <div class="flex justify-between items-center">
      <h3 class="text-lg font-medium">Ürünler</h3>
      <router-link
        :to="{ name: 'products.create' }"
        class="px-4 py-2 bg-[#4F45E4] text-white rounded-lg hover:bg-[#4F45E4]/90 transition-colors"
      >
        Yeni Ürün Ekle
      </router-link>
    </div>

    <!-- Ürün Listesi -->
    <div class="bg-white rounded-lg shadow">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Görsel</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Başlık</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fiyat</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">İşlemler</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="product in products" :key="product.id">
              <td class="px-6 py-4 whitespace-nowrap">
                <img
                  :src="product.image_url"
                  class="h-10 w-10 rounded-full object-cover"
                  alt="Ürün görseli"
                />
              </td>
              <td class="px-6 py-4 whitespace-nowrap">{{ product.title }}</td>
              <td class="px-6 py-4 whitespace-nowrap">{{ product.price }} ₺</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex space-x-2">
                  <router-link
                    :to="{ name: 'products.edit', params: { id: product.id }}"
                    class="text-[#4F45E4] hover:text-[#4F45E4]/80"
                  >
                    Düzenle
                  </router-link>
                  <button
                    @click="handleDelete(product.id)"
                    class="text-red-600 hover:text-red-800"
                  >
                    Sil
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useProductStore } from '../../stores/product'
import { useToast } from '../../composables/useToast'

const productStore = useProductStore()
const toast = useToast()
const products = ref([])

const loadProducts = async () => {
  try {
    products.value = await productStore.fetchProducts()
  } catch (error) {
    toast.error('Ürünler yüklenirken bir hata oluştu')
  }
}

const handleDelete = async (id) => {
  try {
    await productStore.deleteProduct(id)
    toast.success('Ürün başarıyla silindi')
    loadProducts()
  } catch (error) {
    toast.error('Ürün silinirken bir hata oluştu')
  }
}

onMounted(() => {
  loadProducts()
})
</script>
