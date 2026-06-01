import { createRouter, createWebHistory } from 'vue-router'

import DashboardView from '@/views/DashboardView.vue'
import LoginView from '@/views/LoginView.vue'
import ProfilesView from '@/views/ProfilesView.vue'
import UserProfilesView from '@/views/UserProfilesView.vue'
import UsersView from '@/views/UsersView.vue'

function getStoredUser() {
  const storedUser = localStorage.getItem('user')

  return storedUser ? JSON.parse(storedUser) : null
}

function storedUserIsAdmin() {
  const user = getStoredUser()

  return Boolean(user?.profiles?.some((profile) => profile.name === 'Administrador'))
}

const routes = [
  {
    path: '/',
    redirect: '/dashboard',
  },
  {
    path: '/login',
    name: 'login',
    component: LoginView,
    meta: {
      guestOnly: true,
    },
  },
  {
    path: '/dashboard',
    name: 'dashboard',
    component: DashboardView,
    meta: {
      requiresAuth: true,
    },
  },
  {
    path: '/users',
    name: 'users',
    component: UsersView,
    meta: {
      requiresAuth: true,
      requiresAdmin: true,
    },
  },
  {
    path: '/profiles',
    name: 'profiles',
    component: ProfilesView,
    meta: {
      requiresAuth: true,
      requiresAdmin: true,
    },
  },
  {
    path: '/user-profiles',
    name: 'user-profiles',
    component: UserProfilesView,
    meta: {
      requiresAuth: true,
      requiresAdmin: true,
    },
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

router.beforeEach((to) => {
  const token = localStorage.getItem('token')

  if (to.meta.requiresAuth && !token) {
    return '/login'
  }

  if (to.meta.guestOnly && token) {
    return '/dashboard'
  }

  if (to.meta.requiresAdmin && !storedUserIsAdmin()) {
    return {
      path: '/dashboard',
      query: {
        access_denied: 'admin',
      },
    }
  }

  return true
})

export default router
