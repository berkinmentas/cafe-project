import { defineStore } from 'pinia'
import axios from 'axios'

export const useCategoryStore = defineStore('category', {
  state: () => ({
    categories: []
  }),

  actions: {
    async fetchCategories() {
      const response = await axios.get('/api/admin/categories')
      return response.data.data
    },

    async getCategory(id) {
      const response = await axios.get(`/api/admin/categories/${id}`)
      return response.data.data
    },

    async createCategory(data) {
      const formData = new FormData()

        formData.append('title', data.title)
      if (data.image) {
        formData.append('image', data.image)
      }

        const response = await axios.post('/api/admin/categories', formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      })
      return response.data.data
    },

    async updateCategory(data) {
      const formData = new FormData()
      formData.append('_method', 'PUT')
      formData.append('title', data.title)
      if (data.image) {
        formData.append('image', data.image)
      }

      const response = await axios.post(`/api/admin/categories/${data.id}`, formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      })
      return response.data.data
    },

    async deleteCategory(id) {
      const response = await axios.delete(`/api/admin/categories/${id}`)
      return response.data.data
    }
  }
})
