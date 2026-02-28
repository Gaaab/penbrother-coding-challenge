<script setup lang="ts">
import { RouterLink } from 'vue-router'
import StateBadge from './StateBadge.vue'
import type { Amortization } from '@/types'

defineProps<{ items: Amortization[] }>()

function formatAmount(value: number): string {
  return `$${Number(value).toFixed(2)}`
}
</script>

<template>
  <div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <table class="w-full text-sm">
      <thead class="bg-gray-50 text-left text-gray-600">
        <tr>
          <th class="px-4 py-3">ID</th>
          <th class="px-4 py-3">Project</th>
          <th class="px-4 py-3">Amount</th>
          <th class="px-4 py-3">State</th>
          <th class="px-4 py-3">Schedule Date</th>
          <th class="px-4 py-3"></th>
        </tr>
      </thead>
      <tbody class="divide-y">
        <tr v-for="item in items" :key="item.id" class="hover:bg-gray-50">
          <td class="px-4 py-3">{{ item.id }}</td>
          <td class="px-4 py-3">{{ item.project_name }}</td>
          <td class="px-4 py-3">{{ formatAmount(item.amount) }}</td>
          <td class="px-4 py-3"><StateBadge :state="item.state" /></td>
          <td class="px-4 py-3">{{ item.schedule_date }}</td>
          <td class="px-4 py-3">
            <RouterLink :to="`/amortizations/${item.id}`" class="text-blue-600 hover:underline">
              View
            </RouterLink>
          </td>
        </tr>
        <tr v-if="items.length === 0">
          <td colspan="6" class="px-4 py-8 text-center text-gray-400">No amortizations found</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
