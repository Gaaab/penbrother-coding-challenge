<script setup lang="ts">
import { computed, onMounted, watch } from 'vue'
import { useStore } from 'vuex'
import { useRoute } from 'vue-router'
import StateBadge from '@/components/StateBadge.vue'
import PaginationBar from '@/components/PaginationBar.vue'

const store = useStore()
const route = useRoute()

const amortization = computed(() => store.getters['amortizations/currentAmortization'])
const payments = computed(() => store.getters['payments/payments'])
const paymentsPagination = computed(() => store.getters['payments/pagination'])
const loading = computed(
  () => store.getters['amortizations/isLoading'] || store.getters['payments/isLoading']
)

const amortizationId = computed(() => Number(route.params.id))

function formatCents(cents: number): string {
  return `$${(cents / 100).toFixed(2)}`
}

function formatDate(dateStr: string): string {
  return new Date(dateStr + 'T00:00:00').toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

function loadData() {
  store.dispatch('amortizations/fetchAmortization', amortizationId.value)
  store.dispatch('payments/fetchByAmortization', { id: amortizationId.value })
}

function onPaymentPageChange(page: number) {
  store.dispatch('payments/fetchByAmortization', { id: amortizationId.value, page })
}

onMounted(loadData)
watch(amortizationId, loadData)
</script>

<template>
  <div>
    <RouterLink to="/" class="text-blue-600 hover:underline text-sm mb-4 inline-block">
      &larr; Back to list
    </RouterLink>

    <div v-if="loading && !amortization" class="text-center py-8 text-gray-400">Loading...</div>

    <template v-if="amortization">
      <div class="flex items-center gap-4 mb-6">
        <h2 class="text-2xl font-bold text-gray-800">{{ amortization.project_name }}</h2>
        <StateBadge :state="amortization.state" />
      </div>

      <!-- Summary cards -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-sm p-5">
          <span class="text-sm text-gray-500">Amount</span>
          <p class="text-2xl font-semibold text-gray-800 mt-1">
            {{ formatCents(amortization.amount) }}
          </p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-5">
          <span class="text-sm text-gray-500">Schedule Date</span>
          <p class="text-2xl font-semibold text-gray-800 mt-1">
            {{ formatDate(amortization.schedule_date) }}
          </p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-5">
          <span class="text-sm text-gray-500">Total Payments</span>
          <p class="text-2xl font-semibold text-gray-800 mt-1">
            {{ paymentsPagination?.total ?? '—' }}
          </p>
        </div>
      </div>

      <!-- Detail info -->
      <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4">Details</h3>
        <dl class="grid grid-cols-2 md:grid-cols-4 gap-x-6 gap-y-4 text-sm">
          <div>
            <dt class="text-gray-500">Project</dt>
            <dd class="font-medium text-gray-800 mt-0.5">{{ amortization.project_name }}</dd>
          </div>
          <div>
            <dt class="text-gray-500">Project Wallet</dt>
            <dd class="font-medium text-gray-800 mt-0.5">
              {{ formatCents(amortization.project_wallet_balance) }}
            </dd>
          </div>
          <div>
            <dt class="text-gray-500">Promoter ID</dt>
            <dd class="font-medium text-gray-800 mt-0.5">{{ amortization.promoter_id }}</dd>
          </div>
          <div>
            <dt class="text-gray-500">Project ID</dt>
            <dd class="font-medium text-gray-800 mt-0.5">{{ amortization.project_id }}</dd>
          </div>
        </dl>
      </div>

      <!-- Payments table -->
      <div class="flex items-center justify-between mb-3">
        <h3 class="text-lg font-semibold text-gray-800">Payments</h3>
        <span v-if="paymentsPagination" class="text-sm text-gray-500">
          {{ paymentsPagination.total }} payments
        </span>
      </div>
      <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <table class="w-full text-sm">
          <thead class="bg-gray-50 text-left text-gray-600">
            <tr>
              <th class="px-4 py-3">ID</th>
              <th class="px-4 py-3">User</th>
              <th class="px-4 py-3">Amount</th>
              <th class="px-4 py-3">State</th>
              <th class="px-4 py-3">Date</th>
            </tr>
          </thead>
          <tbody class="divide-y">
            <tr v-for="payment in payments" :key="payment.id" class="hover:bg-gray-50">
              <td class="px-4 py-3 text-gray-500">{{ payment.id }}</td>
              <td class="px-4 py-3">{{ payment.user_name }}</td>
              <td class="px-4 py-3">{{ formatCents(payment.amount) }}</td>
              <td class="px-4 py-3"><StateBadge :state="payment.state" /></td>
              <td class="px-4 py-3 text-gray-500">
                {{ new Date(payment.created_at).toLocaleDateString() }}
              </td>
            </tr>
            <tr v-if="payments.length === 0 && !loading">
              <td colspan="5" class="px-4 py-8 text-center text-gray-400">No payments found</td>
            </tr>
          </tbody>
        </table>
      </div>

      <PaginationBar
        v-if="paymentsPagination"
        :meta="paymentsPagination"
        @page-change="onPaymentPageChange"
      />
    </template>
  </div>
</template>
