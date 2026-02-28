<script setup lang="ts">
import SidebarNav from './SidebarNav.vue'
</script>

<template>
  <div class="flex min-h-screen bg-gray-100">
    <SidebarNav />
    <main class="flex-1 p-8">
      <slot />
    </main>
  </div>
</template>
