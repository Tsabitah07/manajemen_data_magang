import axios, { AxiosError } from 'axios'

const API_BASE_URL = import.meta.env.VITE_API_BASE_URL ?? 'http://localhost:8000/api'

export function backendAssetUrl(path: string): string {
  const backendBaseUrl = API_BASE_URL.replace(/\/api\/?$/, '')
  return new URL(path, `${backendBaseUrl}/`).toString()
}

export type UserRole = 'admin' | 'campus-unit' | 'student'
export type ApplicationStatus = 'waiting' | 'accepted' | 'rejected' | 'cancelled' | 'completed'
export type StudentGender = 'male' | 'female'
export type StudentStatus = 'active' | 'inactive' | 'graduated' | 'dropped_out'

export interface StudentProfile {
  gender: StudentGender | null
  nim: string
  major: string
  status: StudentStatus
  enrollment_year: number | null
}

export interface CampusUnitProfile {
  unit_name: string
  position: string
}

export interface User {
  id: string
  name: string
  email: string
  phone?: string | null
  profile_picture?: string | null
  role: UserRole
  student_profile?: StudentProfile | null
  campus_unit_profile?: CampusUnitProfile | null
}

export interface AuthResponse {
  token: string
  data: User
}

export interface Internship {
  id: string
  title: string
  description: string
  start_date: string
  end_date: string
  quota: number
  documentation?: string | null
  campus_unit_id: string
  is_open: boolean
  cv_required: boolean
  created_at: string
  updated_at: string
  campus_unit?: User
}

export type InternshipUpdate = Pick<
  Internship,
  'title' | 'description' | 'start_date' | 'end_date' | 'quota' | 'cv_required'
>

export interface Application {
  id: number | string
  internship_id: string
  student_id: string
  cv?: string | null
  status: ApplicationStatus
  created_at: string
  updated_at: string
  internship?: Internship
  student?: User
}

export interface ApiEnvelope<T> {
  message?: string
  data: T
}

export function unwrapData<T>(response: T | ApiEnvelope<T>): T {
  if (typeof response === 'object' && response !== null && 'data' in response) {
    return response.data
  }

  return response
}

export async function apiRequest<T>(
  path: string,
  options: RequestInit = {},
  token?: string | null,
): Promise<T> {
  const headers = new Headers(options.headers)
  if (options.body && !(options.body instanceof FormData) && !headers.has('Content-Type')) {
    headers.set('Content-Type', 'application/json')
  }
  if (token) headers.set('Authorization', `Bearer ${token}`)

  const requestHeaders: Record<string, string> = {}
  headers.forEach((value, key) => {
    requestHeaders[key] = value
  })

  try {
    const response = await axios.request<T>({
      baseURL: API_BASE_URL,
      url: path,
      method: options.method ?? 'GET',
      data: options.body,
      headers: requestHeaders,
      signal: options.signal ?? undefined,
    })
    return response.data
  } catch (error) {
    if (error instanceof AxiosError) {
      const payload: unknown = error.response?.data
      if (typeof payload === 'object' && payload !== null) {
        if ('message' in payload && typeof payload.message === 'string') {
          throw new Error(payload.message)
        }
        if ('status' in payload && typeof payload.status === 'string') {
          throw new Error(payload.status)
        }
      }
      throw new Error(error.message)
    }
    throw error
  }
}
