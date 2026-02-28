<script setup lang="ts">
import type { PaginationMeta } from '@/types'

const props = defineProps<{ meta: PaginationMeta }>()
const emit = defineEmits<{ (e: 'page-change', page: number): void }>()

function goToPage(page: number) {
  if (page >= 1 && page <= props.meta.last_page) {
    emit('page-change', page)
  }
}
</script>

<template>
  <div class="flex items-center justify-between mt-4">
    <span class="text-sm text-gray-600">
      Page {{ meta.current_page }} of {{ meta.last_page }} ({{ meta.total }} total)
    </span>
    <div class="flex gap-2">
      <button
        class="px-3 py-1 rounded border text-sm disabled:opacity-50"
        :disabled="meta.current_page <= 1"
        @click="goToPage(meta.current_page - 1)"
      >
        Previous
      </button>
      <button
        class="px-3 py-1 rounded border text-sm disabled:opacity-50"
        :disabled="meta.current_page >= meta.last_page"
        @click="goToPage(meta.current_page + 1)"
      >
        Next
      </button>
    </div>
  </div>
</template>
