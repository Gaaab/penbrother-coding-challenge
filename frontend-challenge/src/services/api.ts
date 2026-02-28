import axios from 'axios'
import type {
  Amortization,
  Payment,
  PaginatedResponse,
  AmortizationStats,
  AmortizationFilters
} from '@/types'

const apiClient = axios.create({
  baseURL: import.meta.env.VITE_API_URL || 'http://localhost:8000/api',
  headers: { 'Content-Type': 'application/json' }
})

export function getAmortizations(params: AmortizationFilters = {}) {
  const cleaned = Object.fromEntries(
    Object.entries(params).filter(([, v]) => v !== '' && v !== undefined && v !== null)
  )
  return apiClient.get<PaginatedResponse<Amortization>>('/amortizations', { params: cleaned })
}

export function getAmortization(id: number) {
  return apiClient.post<{ data: Amortization }>(`/amortizations/${id}`)
}

export function getAmortizationPayments(id: number, page = 1) {
  return apiClient.get<PaginatedResponse<Payment>>(`/amortizations/${id}/payments`, {
    params: { page }
  })
}

export function processPayments(date?: string) {
  return apiClient.post<{ message: string; output: string }>('/payments/process', {
    ...(date ? { date } : {})
  })
}

export function getAmortizationStats() {
  return apiClient.get<AmortizationStats>('/amortizations/stats')
}
