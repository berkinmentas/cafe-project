<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <h3 class="text-lg font-medium">Kategori Düzenle</h3>
      <router-link 
        :to="{ name: 'categories' }"
        class="text-[#4F45E4] hover:text-[#4F45E4]/80"
      >
        Geri Dön
      </router-link>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
      <form v-if="category" @submit.prevent="handleSubmit" class="space-y-4">
        <Input
          v-model="form.title"
          label="Başlık"
          placeholder="Kategori başlığı giriniz"
          type="text"
        />

        <div>
          <label class="block text-sm font-medium text-gray-700">Görsel</label>
          <ImageUpload 
            v-model="form.image"
            :preview-url="form.image_url"
          />
        </div>

        <div class="flex justify-end space-x-2">
          <button
            type="submit"
            class="px-4 py-2 bg-[#4F45E4] text-white rounded-lg hover:bg-[#4F45E4]/90 transition-colors"
          >
            Güncelle
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useCategoryStore } from '../../stores/category'
import { useToast } from '../../composables/useToast'
import ImageUpload from '../../components/ImageUpload.vue'
import Input from '../../components/Input.vue'

const router = useRouter()
const route = useRoute()
const categoryStore = useCategoryStore()
const toast = useToast()

const category = ref(null)
const form = ref({
  id: null,
  title: '',
  image: null,
  image_url: ''
})

const loadCategory = async () => {
  try {
    category.value = await categoryStore.getCategory(route.params.id)
    form.value = {
      id: category.value.id,
      title: category.value.title,
      image: null,
      image_url: category.value.image_url
    }
  } catch (error) {
    toast.error(error.response?.data?.message || 'Kategori yüklenirken bir hata oluştu')
    router.push({ name: 'categories' })
  }
}

const handleSubmit = async () => {
  try {
    if (!form.value.title) {
      return toast.error('Kategori başlığı zorunludur')
    }

    await categoryStore.updateCategory(form.value)
    toast.success('Kategori başarıyla güncellendi')
    router.push({ name: 'categories' })
  } catch (error) {
    toast.error(error.response?.data?.message || 'Bir hata oluştu')
  }
}

onMounted(() => {
  loadCategory()
})
</script> 