<script setup lang="ts">
import { RouterLink } from 'vue-router'
</script>

<template>
  <nav class="w-64 min-h-screen bg-gray-900 text-white p-6">
    <h1 class="text-xl font-bold mb-8">Amortizations</h1>
    <ul class="space-y-2">
      <li>
        <RouterLink
          to="/"
          class="block px-4 py-2 rounded hover:bg-gray-700 transition-colors"
          active-class="bg-gray-700"
        >
          List
        </RouterLink>
      </li>
      <li>
        <RouterLink
          to="/metrics"
          class="block px-4 py-2 rounded hover:bg-gray-700 transition-colors"
          active-class="bg-gray-700"
        >
          Metrics
        </RouterLink>
      </li>
    </ul>
  </nav>
</template>
