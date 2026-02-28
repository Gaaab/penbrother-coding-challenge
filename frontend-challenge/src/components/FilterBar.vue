<script setup lang="ts">
import { ref } from 'vue'

const emit = defineEmits<{
  (e: 'filter', filters: { state?: string; amount?: string }): void
}>()

const state = ref('')
const amount = ref('')

function applyFilters() {
  emit('filter', { state: state.value, amount: amount.value })
}

function clearFilters() {
  state.value = ''
  amount.value = ''
  emit('filter', {})
}
</script>

<template>
  <div class="flex flex-wrap items-end gap-4 mb-6 p-4 bg-white rounded-lg shadow-sm">
    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">State</label>
      <select v-model="state" class="border rounded px-3 py-2 text-sm" @change="applyFilters">
        <option value="">All</option>
        <option value="pending">Pending</option>
        <option value="paid">Paid</option>
        <option value="completed">Completed</option>
        <option value="cancelled">Cancelled</option>
      </select>
    </div>
    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">Amount (cents)</label>
      <input
        v-model="amount"
        type="number"
        placeholder="Filter by amount"
        class="border rounded px-3 py-2 text-sm"
        @keyup.enter="applyFilters"
      />
    </div>
    <button
      class="px-4 py-2 bg-gray-600 text-white text-sm rounded hover:bg-gray-700"
      @click="applyFilters"
    >
      Apply
    </button>
    <button class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800" @click="clearFilters">
      Clear
    </button>
  </div>
</template>
