import { createStore } from 'vuex'
import amortizations from '@/store/modules/amortizations'
import payments from '@/store/modules/payments'
import metrics from '@/store/modules/metrics'

export default createStore({
  modules: {
    amortizations,
    payments,
    metrics
  }
})
