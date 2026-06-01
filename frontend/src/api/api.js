import axios from 'axios'

import { clearSession } from '@/stores/authStore'

/**
 * Centralized Axios instance used by the Vue application.
 *
 * It defines the Laravel API base URL, default JSON headers and automatically
 * attaches the Bearer token stored after authentication.
 */
const api = axios.create({
  baseURL: import.meta.env.VITE_API_URL || 'http://localhost:8000/api',
  headers: {
    Accept: 'application/json',
    'Content-Type': 'application/json',
  },
})

/**
 * Adds the current authentication token to every outgoing request.
 */
api.interceptors.request.use((config) => {
  const token = localStorage.getItem('token')

  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }

  return config
})

/**
 * Clears the local session when the API returns an unauthorized response.
 */
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      clearSession()
    }

    return Promise.reject(error)
  },
)

export default api
