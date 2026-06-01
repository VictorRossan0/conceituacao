import api from '@/api/api'

/**
 * Lists profiles from the API.
 *
 * Supports optional soft delete filters:
 * - with_trashed=true
 * - only_trashed=true
 */
export async function listProfiles(params = {}) {
  const response = await api.get('/profiles', { params })
  return response.data
}

/**
 * Creates a new profile.
 */
export async function createProfile(payload) {
  const response = await api.post('/profiles', payload)
  return response.data
}

/**
 * Updates an existing profile by ID.
 */
export async function updateProfile(id, payload) {
  const response = await api.put(`/profiles/${id}`, payload)
  return response.data
}

/**
 * Soft deletes a profile by ID.
 */
export async function deleteProfile(id) {
  const response = await api.delete(`/profiles/${id}`)
  return response.data
}
