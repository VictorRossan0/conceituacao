<script setup>
import { useRouter } from 'vue-router'

import { logout as logoutRequest } from '@/services/authService'
import { clearSession, isAdmin, isAuthenticated } from '@/stores/authStore'

const router = useRouter()

async function handleLogout() {
  try {
    await logoutRequest()
  } catch (error) {
    console.error('Erro ao realizar logout:', error)
  } finally {
    clearSession()
    router.push('/login')
  }
}
</script>

<template>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <RouterLink class="navbar-brand fw-semibold" to="/dashboard"> Conceituação API </RouterLink>

      <button
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#mainNavbar"
        aria-controls="mainNavbar"
        aria-expanded="false"
        aria-label="Alternar navegação"
      >
        <span class="navbar-toggler-icon"></span>
      </button>

      <div id="mainNavbar" class="collapse navbar-collapse">
        <ul v-if="isAuthenticated" class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <RouterLink class="nav-link" to="/dashboard">Dashboard</RouterLink>
          </li>

          <li v-if="isAdmin" class="nav-item">
            <RouterLink class="nav-link" to="/users">Usuários</RouterLink>
          </li>

          <li v-if="isAdmin" class="nav-item">
            <RouterLink class="nav-link" to="/profiles">Perfis</RouterLink>
          </li>

          <li v-if="isAdmin" class="nav-item">
            <RouterLink class="nav-link" to="/user-profiles">Associações</RouterLink>
          </li>
        </ul>

        <div class="ms-auto">
          <button
            v-if="isAuthenticated"
            type="button"
            class="btn btn-outline-light btn-sm"
            @click="handleLogout"
          >
            Sair
          </button>

          <RouterLink v-else class="btn btn-outline-light btn-sm" to="/login"> Login </RouterLink>
        </div>
      </div>
    </div>
  </nav>
</template>
