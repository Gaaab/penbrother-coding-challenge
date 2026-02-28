<script setup lang="ts">
import { computed, onMounted } from 'vue'
import { useStore } from 'vuex'
import FilterBar from '@/components/FilterBar.vue'
import AmortizationTable from '@/components/AmortizationTable.vue'
import PaymentProcessForm from '@/components/PaymentProcessForm.vue'
import PaginationBar from '@/components/PaginationBar.vue'

const store = useStore()

const items = computed(() => store.getters['amortizations/amortizations'])
const pagination = computed(() => store.getters['amortizations/pagination'])
const loading = computed(() => store.getters['amortizations/isLoading'])

onMounted(() => {
  store.dispatch('amortizations/fetchAmortizations')
})

function onFilter(filters: { state?: string; amount_min?: string; amount_max?: string }) {
  store.dispatch('amortizations/setFilters', { ...filters, page: 1 })
}

function onPageChange(page: number) {
  const current = store.getters['amortizations/filters']
  store.dispatch('amortizations/setFilters', { ...current, page })
}
</script>

<template>
  <div>
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Amortizations</h2>

    <PaymentProcessForm />
    <FilterBar @filter="onFilter" />

    <div v-if="loading" class="text-center py-8 text-gray-400">Loading...</div>

    <template v-else>
      <AmortizationTable :items="items" />
      <PaginationBar v-if="pagination" :meta="pagination" @page-change="onPageChange" />
    </template>
  </div>
</template>
