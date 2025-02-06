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

    <!-- Kategori Modal -->
    <Modal v-model="showModal">
      <template #title>
        {{ isEditing ? 'Kategori Düzenle' : 'Yeni Kategori' }}
      </template>

      <template #content>
        <form @submit.prevent="handleSubmit" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">Başlık</label>
            <input
              v-model="form.title"
              type="text"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Görsel</label>
            <ImageUpload
              v-model="form.image"
              :preview-url="form.image_url"
            />
          </div>
        </form>
      </template>
    </Modal>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useCategoryStore } from '../stores/category'
import Modal from '../components/Modal.vue'
import ImageUpload from '../components/ImageUpload.vue'
import { useToast } from '../composables/useToast'

const categoryStore = useCategoryStore()
const toast = useToast()

const categories = ref([])
const showModal = ref(false)
const isEditing = ref(false)
const form = ref({
  title: '',
  image: null,
  image_url: ''
})

const openCreateModal = () => {
  isEditing.value = false
  form.value = {
    title: '',
    image: null,
    image_url: ''
  }
  showModal.value = true
}

const handleEdit = (category) => {
  isEditing.value = true
  form.value = {
    id: category.id,
    title: category.title,
    image: null,
    image_url: category.image_url
  }
  showModal.value = true
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

const loadCategories = async () => {
  try {
    categories.value = await categoryStore.fetchCategories()
  } catch (error) {
    toast.error('Kategoriler yüklenirken bir hata oluştu')
  }
}

const handleSubmit = async () => {
  try {
    if (isEditing.value) {
      await categoryStore.updateCategory(form.value)
      toast.success('Kategori başarıyla güncellendi')
    } else {
      await categoryStore.createCategory(form.value)
      toast.success('Kategori başarıyla oluşturuldu')
    }
    showModal.value = false
    loadCategories()
  } catch (error) {
    toast.error('Bir hata oluştu')
  }
}

onMounted(() => {
  loadCategories()
})
</script>
