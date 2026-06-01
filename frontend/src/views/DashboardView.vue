<script setup>
import { computed, onMounted, ref } from 'vue'
import { useRoute } from 'vue-router'

import BaseAlert from '@/components/BaseAlert.vue'
import { getAuthenticatedUser } from '@/services/authService'
import { updateUserSession } from '@/stores/authStore'

const route = useRoute()

const user = ref(null)
const loading = ref(true)
const errorMessage = ref('')

const accessDeniedMessage = computed(() => {
  if (route.query.access_denied === 'admin') {
    return 'Esta área só pode ser acessada por usuários com perfil Administrador.'
  }

  return ''
})

const userIsAdmin = computed(() => {
  return Boolean(user.value?.profiles?.some((profile) => profile.name === 'Administrador'))
})

onMounted(async () => {
  try {
    const data = await getAuthenticatedUser()
    user.value = data.user
    updateUserSession(data.user)
  } catch {
    errorMessage.value = 'Não foi possível carregar os dados do usuário autenticado.'
  } finally {
    loading.value = false
  }
})
</script>

<template>
  <section>
    <h1 class="h3 mb-3">Dashboard</h1>

    <BaseAlert v-if="accessDeniedMessage" type="warning" :message="accessDeniedMessage" />

    <BaseAlert v-if="errorMessage" type="danger" :message="errorMessage" />

    <div v-if="loading" class="text-muted">Carregando...</div>

    <div v-else-if="user" class="row g-3">
      <div class="col-md-6">
        <div class="card shadow-sm">
          <div class="card-body">
            <h2 class="h5">Usuário autenticado</h2>

            <p class="mb-1"><strong>Nome:</strong> {{ user.name }}</p>
            <p class="mb-1"><strong>E-mail:</strong> {{ user.email }}</p>

            <div class="mt-3">
              <strong>Perfis:</strong>

              <span
                v-for="profile in user.profiles"
                :key="profile.id"
                class="badge text-bg-primary ms-2"
              >
                {{ profile.name }}
              </span>

              <span v-if="user.profiles.length === 0" class="text-muted ms-2">
                Nenhum perfil associado.
              </span>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="card shadow-sm">
          <div class="card-body">
            <h2 class="h5">Módulos disponíveis</h2>

            <div v-if="userIsAdmin" class="d-grid gap-2">
              <RouterLink class="btn btn-outline-primary" to="/users">
                Gerenciar usuários
              </RouterLink>

              <RouterLink class="btn btn-outline-primary" to="/profiles">
                Gerenciar perfis
              </RouterLink>

              <RouterLink class="btn btn-outline-primary" to="/user-profiles">
                Associações usuário-perfis
              </RouterLink>
            </div>

            <div v-else class="alert alert-info mb-0">
              Seu usuário possui acesso ao dashboard. Os módulos administrativos estão disponíveis
              apenas para usuários com perfil Administrador.
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
