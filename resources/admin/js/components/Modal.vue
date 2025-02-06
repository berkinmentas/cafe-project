<template>
  <div v-if="modelValue" class="relative z-50">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black/30" @click="$emit('update:modelValue', false)"></div>

    <!-- Modal -->
    <div class="fixed inset-0 overflow-y-auto">
      <div class="flex min-h-full items-center justify-center p-4">
        <div 
          class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all"
        >
          <!-- Header -->
          <div class="text-lg font-medium leading-6 text-gray-900">
            <slot name="title" />
          </div>

          <!-- Content -->
          <div class="mt-2">
            <slot name="content" />
          </div>

          <!-- Footer -->
          <div class="mt-4 flex justify-end space-x-2">
            <slot name="footer">
              <button
                type="button"
                class="inline-flex justify-center rounded-md border border-transparent bg-[#4F45E4]/10 px-4 py-2 text-sm font-medium text-[#4F45E4] hover:bg-[#4F45E4]/20"
                @click="$emit('update:modelValue', false)"
              >
                Kapat
              </button>
            </slot>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  modelValue: {
    type: Boolean,
    required: true
  }
})

defineEmits(['update:modelValue'])
</script> 