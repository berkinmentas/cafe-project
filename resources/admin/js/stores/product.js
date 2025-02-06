import { defineStore } from 'pinia'
import axios from 'axios'

export const useProductStore = defineStore('product', {
  state: () => ({
    products: []
  }),

  actions: {
    async fetchProducts() {
      const response = await axios.get('/api/admin/products')
      return response.data.data
    },

    async getProduct(id) {
      const response = await axios.get(`/api/admin/products/${id}`)
      return response.data.data
    },

    async createProduct(data) {
      const formData = new FormData()
      formData.append('title', data.title)
      formData.append('description', data.description)
      formData.append('price', data.price)

        if (data.image) {
        formData.append('image', data.image)
      }

      if (data.categories?.length) {
        data.categories.forEach(categoryId => {
          formData.append('categories[]', categoryId)
        })
      }

      const response = await axios.post('/api/admin/products', formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      })
      return response.data.data
    },

    async updateProduct(data) {
      const formData = new FormData()
      formData.append('_method', 'PUT')
      formData.append('title', data.title)
      formData.append('description', data.description)
      formData.append('price', data.price)

      if (data.image) {
        formData.append('image', data.image)
      }

      if (data.categories?.length) {
        data.categories.forEach(categoryId => {
          formData.append('categories[]', categoryId)
        })
      }

      const response = await axios.post(`/api/admin/products/${data.id}`, formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      })
      return response.data.data
    },

    async deleteProduct(id) {
      const response = await axios.delete(`/api/admin/products/${id}`)
      return response.data.data
    }
  }
})
