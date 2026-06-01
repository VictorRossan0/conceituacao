import api from '@/api/api'

/**
 * Lists users from the API.
 *
 * Supports optional soft delete filters:
 * - with_trashed=true
 * - only_trashed=true
 */
export async function listUsers(params = {}) {
  const response = await api.get('/users', { params })
  return response.data
}

/**
 * Creates a new user.
 */
export async function createUser(payload) {
  const response = await api.post('/users', payload)
  return response.data
}

/**
 * Updates an existing user by ID.
 */
export async function updateUser(id, payload) {
  const response = await api.put(`/users/${id}`, payload)
  return response.data
}

/**
 * Soft deletes a user by ID.
 */
export async function deleteUser(id) {
  const response = await api.delete(`/users/${id}`)
  return response.data
}
