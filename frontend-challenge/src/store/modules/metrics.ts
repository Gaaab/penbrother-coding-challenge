import { getAmortizationStats } from '@/services/api'
import type { AmortizationStats } from '@/types'
import type { ActionContext } from 'vuex'

interface MetricsState {
  stats: AmortizationStats | null
  loading: boolean
}

const state: MetricsState = {
  stats: null,
  loading: false
}

const mutations = {
  SET_STATS(state: MetricsState, stats: AmortizationStats) {
    state.stats = stats
  },
  SET_LOADING(state: MetricsState, loading: boolean) {
    state.loading = loading
  }
}

const actions = {
  async fetchStats({ commit }: ActionContext<MetricsState, any>) {
    commit('SET_LOADING', true)
    try {
      const { data } = await getAmortizationStats()
      commit('SET_STATS', data)
    } finally {
      commit('SET_LOADING', false)
    }
  }
}

const getters = {
  stats: (state: MetricsState) => state.stats,
  isLoading: (state: MetricsState) => state.loading
}

export default { namespaced: true, state, mutations, actions, getters }
