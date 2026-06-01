import api from '@/api/api'

/**
 * Lists profiles assigned to a specific user.
 */
export async function listUserProfiles(userId) {
  const response = await api.get(`/users/${userId}/profiles`)
  return response.data
}

/**
 * Associates a profile with a user.
 */
export async function attachProfileToUser(userId, profileId) {
  const response = await api.post(`/users/${userId}/profiles`, {
    profile_id: profileId,
  })

  return response.data
}

/**
 * Removes the association between a user and a profile.
 */
export async function detachProfileFromUser(userId, profileId) {
  const response = await api.delete(`/users/${userId}/profiles/${profileId}`)
  return response.data
}
