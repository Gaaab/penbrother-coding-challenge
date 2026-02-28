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
      <h2 class="text-2xl font-bold text-gray-800 mb-6">Amortization #{{ amortization.id }}</h2>

      <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
          <div>
            <span class="text-sm text-gray-500">Amount</span>
            <p class="font-medium">{{ formatCents(amortization.amount) }}</p>
          </div>
          <div>
            <span class="text-sm text-gray-500">State</span>
            <p><StateBadge :state="amortization.state" /></p>
          </div>
          <div>
            <span class="text-sm text-gray-500">Schedule Date</span>
            <p class="font-medium">{{ amortization.schedule_date }}</p>
          </div>
          <div>
            <span class="text-sm text-gray-500">Project</span>
            <p class="font-medium">{{ amortization.project_name }}</p>
          </div>
          <div>
            <span class="text-sm text-gray-500">Project Wallet</span>
            <p class="font-medium">{{ formatCents(amortization.project_wallet_balance) }}</p>
          </div>
        </div>
      </div>

      <h3 class="text-lg font-semibold text-gray-800 mb-3">Payments</h3>
      <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <table class="w-full text-sm">
          <thead class="bg-gray-50 text-left text-gray-600">
            <tr>
              <th class="px-4 py-3">ID</th>
              <th class="px-4 py-3">User</th>
              <th class="px-4 py-3">Amount</th>
              <th class="px-4 py-3">State</th>
            </tr>
          </thead>
          <tbody class="divide-y">
            <tr v-for="payment in payments" :key="payment.id" class="hover:bg-gray-50">
              <td class="px-4 py-3">{{ payment.id }}</td>
              <td class="px-4 py-3">{{ payment.user_name }}</td>
              <td class="px-4 py-3">{{ formatCents(payment.amount) }}</td>
              <td class="px-4 py-3"><StateBadge :state="payment.state" /></td>
            </tr>
            <tr v-if="payments.length === 0">
              <td colspan="4" class="px-4 py-8 text-center text-gray-400">No payments found</td>
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
