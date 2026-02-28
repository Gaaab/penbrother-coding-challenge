<script setup lang="ts">
defineProps<{ state: string }>()

const colorMap: Record<string, string> = {
  pending: 'bg-yellow-100 text-yellow-800',
  paid: 'bg-green-100 text-green-800',
  completed: 'bg-blue-100 text-blue-800',
  cancelled: 'bg-red-100 text-red-800'
}
</script>

<template>
  <span
    class="px-2 py-1 rounded-full text-xs font-medium"
    :class="colorMap[state] || 'bg-gray-100 text-gray-800'"
  >
    {{ state }}
  </span>
</template>
