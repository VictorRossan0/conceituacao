<script setup>
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'

import BaseAlert from '@/components/BaseAlert.vue'
import { getAuthenticatedUser, login } from '@/services/authService'
import { setSession } from '@/stores/authStore'

const router = useRouter()

const form = reactive({
  email: 'admin@example.com',
  password: 'password',
})

const loading = ref(false)
const errorMessage = ref('')

async function handleLogin() {
  loading.value = true
  errorMessage.value = ''

  try {
    const loginData = await login(form)

    localStorage.setItem('token', loginData.token)

    const authenticatedData = await getAuthenticatedUser()

    setSession(loginData.token, authenticatedData.user)

    router.push('/dashboard')
  } catch (error) {
    errorMessage.value =
      error.response?.data?.message ||
      error.response?.data?.errors?.email?.[0] ||
      'Não foi possível realizar o login.'
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="card shadow-sm">
        <div class="card-body">
          <h1 class="h4 mb-3">Login</h1>

          <BaseAlert v-if="errorMessage" type="danger" :message="errorMessage" />

          <form @submit.prevent="handleLogin">
            <div class="mb-3">
              <label class="form-label" for="email">E-mail</label>
              <input id="email" v-model="form.email" type="email" class="form-control" required />
            </div>

            <div class="mb-3">
              <label class="form-label" for="password">Senha</label>
              <input
                id="password"
                v-model="form.password"
                type="password"
                class="form-control"
                required
              />
            </div>

            <button type="submit" class="btn btn-primary w-100" :disabled="loading">
              {{ loading ? 'Entrando...' : 'Entrar' }}
            </button>
          </form>

          <p class="text-muted small mt-3 mb-0">
            Usuário padrão: <strong>admin@example.com</strong> / <strong>password</strong>
          </p>
        </div>
      </div>
    </div>
  </div>
</template>
