<template>
  <div class="space-y-6">
    <!-- Üst Başlık ve Aksiyon Alanı -->
    <div class="flex justify-between items-center">
      <h3 class="text-lg font-medium">Kategoriler</h3>
      <router-link 
        :to="{ name: 'categories.create' }"
        class="px-4 py-2 bg-[#4F45E4] text-white rounded-lg hover:bg-[#4F45E4]/90 transition-colors"
      >
        Yeni Kategori Ekle
      </router-link>
    </div>

    <!-- Kategori Listesi -->
    <div class="bg-white rounded-lg shadow">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Görsel</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Başlık</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Slug</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">İşlemler</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="category in categories" :key="category.id">
              <td class="px-6 py-4 whitespace-nowrap">
                <img 
                  :src="category.image_url" 
                  class="h-10 w-10 rounded-full object-cover"
                  alt="Kategori görseli"
                />
              </td>
              <td class="px-6 py-4 whitespace-nowrap">{{ category.title }}</td>
              <td class="px-6 py-4 whitespace-nowrap">{{ category.slug }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex space-x-2">
                  <router-link 
                    :to="{ name: 'categories.edit', params: { id: category.id }}"
                    class="text-[#4F45E4] hover:text-[#4F45E4]/80"
                  >
                    Düzenle
                  </router-link>
                  <button 
                    @click="handleDelete(category.id)"
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
import { useCategoryStore } from '../../stores/category'
import { useToast } from '../../composables/useToast'

const categoryStore = useCategoryStore()
const toast = useToast()
const categories = ref([])

const loadCategories = async () => {
  try {
    categories.value = await categoryStore.fetchCategories()
  } catch (error) {
    toast.error('Kategoriler yüklenirken bir hata oluştu')
  }
}

const handleDelete = async (id) => {
  try {
    await categoryStore.deleteCategory(id)
    toast.success('Kategori başarıyla silindi')
    loadCategories()
  } catch (error) {
    toast.error('Kategori silinirken bir hata oluştu')
  }
}

onMounted(() => {
  loadCategories()
})
</script> 