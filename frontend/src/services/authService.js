import api from '@/api/api'

/**
 * Authenticates a user and returns the API token response.
 */
export async function login(credentials) {
  const response = await api.post('/login', credentials)
  return response.data
}

/**
 * Registers a new user in the backend.
 */
export async function register(payload) {
  const response = await api.post('/register', payload)
  return response.data
}

/**
 * Retrieves the authenticated user with assigned profiles.
 */
export async function getAuthenticatedUser() {
  const response = await api.get('/me')
  return response.data
}

/**
 * Revokes the current authenticated token.
 */
export async function logout() {
  const response = await api.post('/logout')
  return response.data
}
