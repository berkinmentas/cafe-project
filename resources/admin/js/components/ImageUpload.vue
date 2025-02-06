<template>
  <div class="space-y-2">
    <div
      class="flex justify-center px-6 pt-5 pb-6 border-2 border-gray-200 border-dashed rounded-md hover:border-[#4F45E4]/50 transition-colors"
      @dragover.prevent
      @drop.prevent="handleDrop"
    >
      <div class="space-y-1 text-center">
        <div v-if="loading" class="mb-4">
          <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-[#4F45E4] mx-auto"></div>
        </div>

        <div v-else-if="previewImage" class="mb-4">
          <img :src="previewImage" class="mx-auto h-32 w-32 object-cover rounded" />
        </div>

        <div class="flex text-sm text-gray-600">
          <label class="relative cursor-pointer bg-white rounded-md font-medium text-[#4F45E4] hover:text-[#4F45E4]/80">
            <span>Dosya Yükle</span>
            <input
              type="file"
              class="sr-only"
              accept="image/*"
              @change="handleFileChange"
              :disabled="loading"
            >
          </label>
          <p class="pl-1">veya sürükleyip bırakın</p>
        </div>
        <p class="text-xs text-gray-500">PNG, JPG, GIF max 2MB</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
  modelValue: {
    type: [File, null],
    default: null
  },
  previewUrl: {
    type: String,
    default: ''
  }
})

const emit = defineEmits(['update:modelValue'])
const previewImage = ref(props.previewUrl)
const loading = ref(false)

const createPreview = (file) => {
  if (file) {
    loading.value = true
    const reader = new FileReader()
    reader.onload = (e) => {
      previewImage.value = e.target.result
      loading.value = false
    }
    reader.readAsDataURL(file)
  }
}

const handleFileChange = (event) => {
  const file = event.target.files[0]
  if (file) {
    emit('update:modelValue', file)
    createPreview(file)
  }
}

const handleDrop = (event) => {
  const file = event.dataTransfer.files[0]
  if (file) {
    emit('update:modelValue', file)
    createPreview(file)
  }
}

watch(() => props.previewUrl, (newValue) => {
  if (newValue) {
    previewImage.value = newValue
  }
}, { immediate: true })

watch(() => props.modelValue, (newValue) => {
  if (newValue instanceof File) {
    createPreview(newValue)
  }
})
</script>
