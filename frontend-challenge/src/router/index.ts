import { createRouter, createWebHistory } from 'vue-router'
import AmortizationList from '../views/AmortizationList.vue'
import AmortizationDetail from '../views/AmortizationDetail.vue'
import MetricsDashboard from '../views/MetricsDashboard.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'list',
      component: AmortizationList
    },
    {
      path: '/amortizations/:id',
      name: 'detail',
      component: AmortizationDetail,
      props: true
    },
    {
      path: '/metrics',
      name: 'metrics',
      component: MetricsDashboard
    }
  ]
})

export default router
