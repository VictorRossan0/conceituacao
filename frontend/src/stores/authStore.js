import { computed, reactive } from 'vue'

const storedUser = localStorage.getItem('user')

/**
 * Reactive authentication state shared across the frontend.
 *
 * This store keeps the navbar, route guards and dashboard synchronized after
 * login/logout without requiring a full page refresh.
 */
const state = reactive({
  token: localStorage.getItem('token'),
  user: storedUser ? JSON.parse(storedUser) : null,
})

export const authState = state

/**
 * Indicates whether there is an active authenticated session.
 */
export const isAuthenticated = computed(() => Boolean(state.token))

/**
 * Indicates whether the authenticated user has the Administrador profile.
 */
export const isAdmin = computed(() => {
  return Boolean(state.user?.profiles?.some((profile) => profile.name === 'Administrador'))
})

/**
 * Persists a new authenticated session.
 */
export function setSession(token, user) {
  state.token = token
  state.user = user

  localStorage.setItem('token', token)
  localStorage.setItem('user', JSON.stringify(user))
}

/**
 * Updates the authenticated user data while keeping the current token.
 */
export function updateUserSession(user) {
  state.user = user
  localStorage.setItem('user', JSON.stringify(user))
}

/**
 * Clears the current authenticated session from memory and localStorage.
 */
export function clearSession() {
  state.token = null
  state.user = null

  localStorage.removeItem('token')
  localStorage.removeItem('user')
}
