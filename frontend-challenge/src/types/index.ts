export interface Amortization {
  id: number
  promoter_id: number
  project_id: number
  amount: number
  state: 'pending' | 'paid' | 'completed' | 'cancelled'
  schedule_date: string
  created_at: string
  updated_at: string
  project_name?: string
  project_wallet_balance?: number
}

export interface Payment {
  id: number
  user_id: number
  amortization_id: number
  amount: number
  state: string
  created_at: string
  updated_at: string
  user_name?: string
}

export interface PaginationMeta {
  current_page: number
  last_page: number
  per_page: number
  total: number
}

export interface PaginatedResponse<T> {
  data: T[]
  meta: PaginationMeta
  links: {
    first: string | null
    last: string | null
    prev: string | null
    next: string | null
  }
}

export interface AmortizationStats {
  total: number
  paid: number
  pending: number
  completed: number
  cancelled: number
  on_time: number
  delayed: number
}

export interface AmortizationFilters {
  state?: string
  amount?: number
  page?: number
}
