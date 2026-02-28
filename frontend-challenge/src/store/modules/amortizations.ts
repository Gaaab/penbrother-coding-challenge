import { getAmortizations, getAmortization, processPayments } from '@/services/api'
import type { Amortization, PaginationMeta, AmortizationFilters } from '@/types'
import type { ActionContext } from 'vuex'

interface AmortizationsState {
  items: Amortization[]
  current: Amortization | null
  pagination: PaginationMeta | null
  filters: AmortizationFilters
  loading: boolean
}

const state: AmortizationsState = {
  items: [],
  current: null,
  pagination: null,
  filters: {},
  loading: false
}

const mutations = {
  SET_ITEMS(state: AmortizationsState, items: Amortization[]) {
    state.items = items
  },
  SET_CURRENT(state: AmortizationsState, item: Amortization | null) {
    state.current = item
  },
  SET_PAGINATION(state: AmortizationsState, meta: PaginationMeta) {
    state.pagination = meta
  },
  SET_FILTERS(state: AmortizationsState, filters: AmortizationFilters) {
    state.filters = filters
  },
  SET_LOADING(state: AmortizationsState, loading: boolean) {
    state.loading = loading
  }
}

const actions = {
  async fetchAmortizations({ commit, state: s }: ActionContext<AmortizationsState, any>) {
    commit('SET_LOADING', true)
    try {
      const { data } = await getAmortizations(s.filters)
      commit('SET_ITEMS', data.data)
      commit('SET_PAGINATION', data.meta)
    } finally {
      commit('SET_LOADING', false)
    }
  },

  async fetchAmortization({ commit }: ActionContext<AmortizationsState, any>, id: number) {
    commit('SET_LOADING', true)
    try {
      const { data } = await getAmortization(id)
      commit('SET_CURRENT', data.data)
    } finally {
      commit('SET_LOADING', false)
    }
  },

  async processPayments(_: ActionContext<AmortizationsState, any>, date?: string) {
    return processPayments(date)
  },

  setFilters(
    { commit, dispatch }: ActionContext<AmortizationsState, any>,
    filters: AmortizationFilters
  ) {
    commit('SET_FILTERS', filters)
    dispatch('fetchAmortizations')
  }
}

const getters = {
  amortizations: (state: AmortizationsState) => state.items,
  currentAmortization: (state: AmortizationsState) => state.current,
  pagination: (state: AmortizationsState) => state.pagination,
  isLoading: (state: AmortizationsState) => state.loading,
  filters: (state: AmortizationsState) => state.filters
}

export default { namespaced: true, state, mutations, actions, getters }
