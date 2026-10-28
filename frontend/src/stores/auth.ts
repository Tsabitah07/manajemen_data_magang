import { defineStore } from 'pinia'
import { computed, ref } from 'vue'
import { apiRequest, type AuthResponse, type User } from '@/services/api'

const TOKEN_KEY = 'ruangmagang_token'
const USER_KEY = 'ruangmagang_user'

function readStoredUser(): User | null {
  const savedUser = localStorage.getItem(USER_KEY)
  if (!savedUser) return null

  try {
    const parsed: unknown = JSON.parse(savedUser)
    if (
      typeof parsed === 'object' &&
      parsed !== null &&
      'id' in parsed &&
      typeof parsed.id === 'string' &&
      'name' in parsed &&
      typeof parsed.name === 'string' &&
      'email' in parsed &&
      typeof parsed.email === 'string' &&
      'role' in parsed &&
      (parsed.role === 'admin' || parsed.role === 'campus-unit' || parsed.role === 'student')
    ) {
      return parsed as User
    }
    localStorage.removeItem(TOKEN_KEY)
    localStorage.removeItem(USER_KEY)
    return null
  } catch {
    localStorage.removeItem(TOKEN_KEY)
    localStorage.removeItem(USER_KEY)
    return null
  }
}

export const useAuthStore = defineStore('auth', () => {
  const token = ref<string | null>(localStorage.getItem(TOKEN_KEY))
  const user = ref<User | null>(readStoredUser())
  const isAuthenticated = computed(() => Boolean(token.value && user.value))

  function setSession(session: AuthResponse) {
    token.value = session.token
    user.value = session.data
    localStorage.setItem(TOKEN_KEY, session.token)
    localStorage.setItem(USER_KEY, JSON.stringify(session.data))
  }

  async function login(email: string, password: string) {
    const session = await apiRequest<AuthResponse>('/auth/login', {
      method: 'POST',
      body: JSON.stringify({ email, password }),
    })
    setSession(session)
  }

  async function register(payload: {
    name: string
    email: string
    password: string
  }) {
    const session = await apiRequest<AuthResponse>('/auth/register', {
      method: 'POST',
      body: JSON.stringify(payload),
    })
    setSession(session)
  }

  function updateUser(updatedUser: User) {
    user.value = updatedUser
    localStorage.setItem(USER_KEY, JSON.stringify(updatedUser))
  }

  async function logout() {
    try {
      if (token.value) {
        await apiRequest('/auth/logout', { method: 'POST' }, token.value)
      }
    } finally {
      token.value = null
      user.value = null
      localStorage.removeItem(TOKEN_KEY)
      localStorage.removeItem(USER_KEY)
    }
  }

  return { token, user, isAuthenticated, login, register, updateUser, logout }
})
