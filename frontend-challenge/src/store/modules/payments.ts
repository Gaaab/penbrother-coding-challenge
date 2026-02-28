import { getAmortizationPayments } from '@/services/api'
import type { Payment, PaginationMeta } from '@/types'
import type { ActionContext } from 'vuex'

interface PaymentsState {
  items: Payment[]
  pagination: PaginationMeta | null
  loading: boolean
}

const state: PaymentsState = {
  items: [],
  pagination: null,
  loading: false
}

const mutations = {
  SET_ITEMS(state: PaymentsState, items: Payment[]) {
    state.items = items
  },
  SET_PAGINATION(state: PaymentsState, meta: PaginationMeta) {
    state.pagination = meta
  },
  SET_LOADING(state: PaymentsState, loading: boolean) {
    state.loading = loading
  }
}

const actions = {
  async fetchByAmortization(
    { commit }: ActionContext<PaymentsState, any>,
    { id, page = 1 }: { id: number; page?: number }
  ) {
    commit('SET_LOADING', true)
    try {
      const { data } = await getAmortizationPayments(id, page)
      commit('SET_ITEMS', data.data)
      commit('SET_PAGINATION', data.meta)
    } finally {
      commit('SET_LOADING', false)
    }
  }
}

const getters = {
  payments: (state: PaymentsState) => state.items,
  pagination: (state: PaymentsState) => state.pagination,
  isLoading: (state: PaymentsState) => state.loading
}

export default { namespaced: true, state, mutations, actions, getters }
