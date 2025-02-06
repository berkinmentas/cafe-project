<template>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-medium">Ürün Düzenle</h3>
            <router-link :to="{ name: 'products' }" class="text-[#4F45E4] hover:text-[#4F45E4]/80">
                Geri Dön
            </router-link>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <form v-if="product" @submit.prevent="handleSubmit" class="space-y-4">
                <Input v-model="form.title" label="Başlık" placeholder="Ürün başlığı giriniz" type="text" />

                <Input v-model="form.price" label="Fiyat" placeholder="Ürün fiyatı giriniz" type="number" />

                <div>
                    <label class="block text-sm font-medium text-gray-700">Açıklama</label>
                    <textarea v-model="form.description" rows="4"
                              class="mt-1 block w-full rounded-md p-2 border border-gray-300 shadow-sm focus:border-[#4F45E4] focus:ring-[#4F45E4]"
                              placeholder="Ürün açıklaması giriniz"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategoriler</label>
                    <Multiselect
                        v-model="form.categories"
                        :options="categories"
                        :multiple="true"
                        track-by="id"
                        label="title"
                        placeholder="Kategorileri seçin"
                        class="multiselect-custom"
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Görsel</label>
                    <ImageUpload v-model="form.image" :preview-url="form.image_url" />
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="submit" class="px-4 py-2 bg-[#4F45E4] text-white rounded-lg hover:bg-[#4F45E4]/90 transition-colors">
                        Güncelle
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import {ref, onMounted} from 'vue'
import {useRouter, useRoute} from 'vue-router'
import {useProductStore} from '@/stores/product.js'
import {useCategoryStore} from '@/stores/category.js'
import {useToast} from '@/composables/useToast.js'
import ImageUpload from '../../components/ImageUpload.vue'
import Input from '../../components/Input.vue'
import Multiselect from 'vue-multiselect'
import '~/vue-multiselect/dist/vue-multiselect.css'

const router = useRouter()
const route = useRoute()
const productStore = useProductStore()
const categoryStore = useCategoryStore()
const toast = useToast()

const product = ref(null)
const categories = ref([])
const form = ref({
    id: null,
    title: '',
    price: '',
    description: '',
    image: null,
    image_url: '',
    categories: []
})

onMounted(async () => {
    try {
        const [productData, categoriesData] = await Promise.all([
            productStore.getProduct(route.params.id),
            categoryStore.fetchCategories()
        ])

        categories.value = categoriesData
        product.value = productData

        form.value = {
            id: product.value.id,
            title: product.value.title,
            price: product.value.price,
            description: product.value.description,
            image: null,
            image_url: product.value.image_url,
            categories: product.value.categories
        }
    } catch (error) {
        toast.error('Veriler yüklenirken bir hata oluştu')
        router.push({name: 'products'})
    }
})

const handleSubmit = async () => {
    try {
        if (!form.value.title) return toast.error('Ürün başlığı zorunludur')
        if (!form.value.price) return toast.error('Ürün fiyatı zorunludur')
        if (!form.value.description) return toast.error('Ürün açıklaması zorunludur')
        if (!form.value.categories.length) return toast.error('En az bir kategori seçmelisiniz')

        const formData = {
            ...form.value,
            categories: form.value.categories.map(cat => cat.id)
        }

        await productStore.updateProduct(formData)
        toast.success('Ürün başarıyla güncellendi')
        router.push({name: 'products'})
    } catch (error) {
        toast.error(error.response?.data?.message || 'Bir hata oluştu')
    }
}
</script>

<style>
.multiselect-custom {
    --ms-tag-bg: #4F45E4;
    --ms-tag-color: white;
}

.multiselect-custom .multiselect__tags {
    border-color: #d1d5db;
    border-radius: 0.375rem;
    min-height: 42px;
    padding: 8px;
}

.multiselect-custom .multiselect__tag {
    background: var(--ms-tag-bg);
    color: var(--ms-tag-color);
    border-radius: 4px;
    margin: 2px;
}

.multiselect-custom .multiselect__tag-icon:after {
    color: var(--ms-tag-color);
}

.multiselect-custom .multiselect__option--highlight {
    background: #4F45E4;
}

.multiselect-custom .multiselect__option--selected {
    background: #4F45E4;
    color: white;
}
</style>
