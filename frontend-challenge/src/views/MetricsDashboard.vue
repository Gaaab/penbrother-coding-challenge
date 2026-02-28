<script setup lang="ts">
import { computed, onMounted } from 'vue'
import { useStore } from 'vuex'
import { Doughnut, Bar } from 'vue-chartjs'
import {
  Chart as ChartJS,
  ArcElement,
  BarElement,
  CategoryScale,
  LinearScale,
  Tooltip,
  Legend
} from 'chart.js'

ChartJS.register(ArcElement, BarElement, CategoryScale, LinearScale, Tooltip, Legend)

const store = useStore()
const stats = computed(() => store.getters['metrics/stats'])
const loading = computed(() => store.getters['metrics/isLoading'])

onMounted(() => {
  store.dispatch('metrics/fetchStats')
})

const stateChartData = computed(() => ({
  labels: ['Paid', 'Pending', 'Completed', 'Cancelled'],
  datasets: [
    {
      data: [
        stats.value?.paid,
        stats.value?.pending,
        stats.value?.completed,
        stats.value?.cancelled
      ],
      backgroundColor: ['#22c55e', '#eab308', '#3b82f6', '#ef4444']
    }
  ]
}))

const timingChartData = computed(() => ({
  labels: ['On Time', 'Delayed'],
  datasets: [
    {
      label: 'Amortizations',
      data: [stats.value?.on_time, stats.value?.delayed],
      backgroundColor: ['#22c55e', '#ef4444']
    }
  ]
}))
</script>

<template>
  <div>
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Metrics</h2>

    <div v-if="loading" class="text-center py-8 text-gray-400">Loading...</div>

    <div v-else-if="stats" class="grid grid-cols-1 md:grid-cols-2 gap-8">
      <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Amortizations by State</h3>
        <Doughnut :data="stateChartData" />
      </div>

      <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">On Time vs Delayed</h3>
        <Bar :data="timingChartData" />
      </div>
    </div>
  </div>
</template>
