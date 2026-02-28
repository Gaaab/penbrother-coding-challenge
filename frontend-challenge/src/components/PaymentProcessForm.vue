<script setup lang="ts">
import { ref } from 'vue'
import { useStore } from 'vuex'

const store = useStore()
const date = ref('')
const processing = ref(false)
const error = ref('')

interface ProcessResult {
  message: string
  processed: number
  delayed: number
  total_payments: number
  total_amount: number
  date: string
}

const result = ref<ProcessResult | null>(null)

function formatAmount(value: number): string {
  return `$${Number(value).toFixed(2)}`
}

async function submit() {
  processing.value = true
  error.value = ''
  result.value = null

  try {
    const { data } = await store.dispatch('amortizations/processPayments', date.value || undefined)
    result.value = data
    store.dispatch('amortizations/fetchAmortizations')
  } catch (e: any) {
    error.value = e.response?.data?.message || 'Failed to process payments'
  } finally {
    processing.value = false
  }
}
</script>

<template>
  <div class="bg-white rounded-lg shadow-sm mb-6">
    <form @submit.prevent="submit" class="p-4">
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
    </form>

    <!-- Results -->
    <div v-if="result" class="border-t px-4 py-4">
      <p class="text-sm font-medium text-gray-800 mb-3">{{ result.message }}</p>
      <div v-if="result.processed > 0" class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div>
          <span class="text-xs text-gray-500">Amortizations</span>
          <p class="text-lg font-semibold text-gray-800">{{ result.processed }}</p>
        </div>
        <div>
          <span class="text-xs text-gray-500">Payments Updated</span>
          <p class="text-lg font-semibold text-gray-800">{{ result.total_payments.toLocaleString() }}</p>
        </div>
        <div>
          <span class="text-xs text-gray-500">Total Amount</span>
          <p class="text-lg font-semibold text-gray-800">{{ formatAmount(result.total_amount) }}</p>
        </div>
        <div>
          <span class="text-xs text-gray-500">Delayed</span>
          <p class="text-lg font-semibold" :class="result.delayed > 0 ? 'text-red-600' : 'text-green-600'">
            {{ result.delayed }}
          </p>
        </div>
      </div>
    </div>

    <p v-if="error" class="px-4 pb-4 text-sm text-red-600">{{ error }}</p>
  </div>
</template>
