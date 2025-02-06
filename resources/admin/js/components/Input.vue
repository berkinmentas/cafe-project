<template>
  <div class="space-y-1.5">
    <label 
      v-if="label"
      :for="id" 
      class="block text-sm font-medium text-gray-700"
    >
      {{ label }}
    </label>
    
    <input
      :id="id"
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
      v-bind="$attrs"
      class="block w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-gray-900 focus:border-[#4F45E4] focus:ring-[#4F45E4] focus:ring-opacity-50 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500"
    />
    
    <p 
      v-if="error"
      class="text-sm text-red-500"
    >
      {{ error }}
    </p>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  modelValue: {
    type: [String, Number],
    default: ''
  },
  label: {
    type: String,
    default: ''
  },
  error: {
    type: String,
    default: ''
  },
  id: {
    type: String,
    default: () => `input-${Math.random().toString(36).substr(2, 9)}`
  }
})

defineEmits(['update:modelValue'])
</script> 