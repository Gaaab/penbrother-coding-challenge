<script setup lang="ts">
import { ref } from 'vue'
import { useStore } from 'vuex'

const store = useStore()
const date = ref('')
const processing = ref(false)
const message = ref('')
const error = ref('')

async function submit() {
  processing.value = true
  message.value = ''
  error.value = ''

  try {
    const { data } = await store.dispatch('amortizations/processPayments', date.value || undefined)
    message.value = data.message
    store.dispatch('amortizations/fetchAmortizations')
  } catch (e: any) {
    error.value = e.response?.data?.message || 'Failed to process payments'
  } finally {
    processing.value = false
  }
}
</script>

<template>
  <form @submit.prevent="submit" class="p-4 bg-white rounded-lg shadow-sm mb-6">
    <h3 class="font-medium text-gray-800 mb-3">Process Payments</h3>
    <div class="flex items-end gap-4">
      <div>
        <label class="block text-sm text-gray-600 mb-1">Date (optional, defaults to today)</label>
        <input v-model="date" type="date" class="border rounded px-3 py-2 text-sm" />
      </div>
      <button
        type="submit"
        :disabled="processing"
        class="px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 disabled:opacity-50"
      >
        {{ processing ? 'Processing...' : 'Process Payments' }}
      </button>
    </div>
    <p v-if="message" class="mt-2 text-sm text-green-600">{{ message }}</p>
    <p v-if="error" class="mt-2 text-sm text-red-600">{{ error }}</p>
  </form>
</template>
