<script setup>
import { onMounted, reactive, ref } from 'vue'

import BaseAlert from '@/components/BaseAlert.vue'
import { createUser, deleteUser, listUsers, updateUser } from '@/services/userService'

const users = ref([])
const loading = ref(false)
const successMessage = ref('')
const errorMessage = ref('')
const filter = ref('active')

const form = reactive({
  id: null,
  name: '',
  email: '',
  password: '',
})

function resetMessages() {
  successMessage.value = ''
  errorMessage.value = ''
}

function resetForm() {
  form.id = null
  form.name = ''
  form.email = ''
  form.password = ''
}

function getFilterParams() {
  if (filter.value === 'with_trashed') {
    return {
      with_trashed: true,
    }
  }

  if (filter.value === 'only_trashed') {
    return {
      only_trashed: true,
    }
  }

  return {}
}

async function loadUsers() {
  loading.value = true
  resetMessages()

  try {
    const response = await listUsers(getFilterParams())
    users.value = response.data
  } catch {
    errorMessage.value = 'Não foi possível carregar os usuários.'
  } finally {
    loading.value = false
  }
}

async function handleSubmit() {
  resetMessages()

  try {
    const payload = {
      name: form.name,
      email: form.email,
    }

    if (!form.id || form.password) {
      payload.password = form.password
    }

    if (form.id) {
      await updateUser(form.id, payload)
      successMessage.value = 'Usuário atualizado com sucesso.'
    } else {
      await createUser(payload)
      successMessage.value = 'Usuário criado com sucesso.'
    }

    resetForm()
    await loadUsers()
  } catch (error) {
    errorMessage.value =
      error.response?.data?.message ||
      Object.values(error.response?.data?.errors || {})?.[0]?.[0] ||
      'Não foi possível salvar o usuário.'
  }
}

function editUser(user) {
  resetMessages()

  form.id = user.id
  form.name = user.name
  form.email = user.email
  form.password = ''
}

async function handleDelete(user) {
  const confirmed = confirm(`Deseja excluir o usuário ${user.name}?`)

  if (!confirmed) {
    return
  }

  resetMessages()

  try {
    await deleteUser(user.id)
    successMessage.value = 'Usuário excluído com sucesso.'
    await loadUsers()
  } catch {
    errorMessage.value = 'Não foi possível excluir o usuário.'
  }
}

onMounted(loadUsers)
</script>

<template>
  <section>
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div>
        <h1 class="h3 mb-1">Usuários</h1>
        <p class="text-muted mb-0">Gerenciamento de usuários da aplicação.</p>
      </div>

      <RouterLink class="btn btn-outline-secondary btn-sm" to="/dashboard"> Voltar </RouterLink>
    </div>

    <BaseAlert v-if="successMessage" type="success" :message="successMessage" />
    <BaseAlert v-if="errorMessage" type="danger" :message="errorMessage" />

    <div class="row g-4">
      <div class="col-lg-4">
        <div class="card shadow-sm">
          <div class="card-body">
            <h2 class="h5 mb-3">
              {{ form.id ? 'Editar usuário' : 'Criar usuário' }}
            </h2>

            <form @submit.prevent="handleSubmit">
              <div class="mb-3">
                <label class="form-label" for="name">Nome</label>
                <input id="name" v-model="form.name" type="text" class="form-control" required />
              </div>

              <div class="mb-3">
                <label class="form-label" for="email">E-mail</label>
                <input id="email" v-model="form.email" type="email" class="form-control" required />
              </div>

              <div class="mb-3">
                <label class="form-label" for="password"> Senha </label>
                <input
                  id="password"
                  v-model="form.password"
                  type="password"
                  class="form-control"
                  :required="!form.id"
                />
                <div v-if="form.id" class="form-text">
                  Deixe em branco para manter a senha atual.
                </div>
              </div>

              <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                  {{ form.id ? 'Atualizar' : 'Criar' }}
                </button>

                <button
                  v-if="form.id"
                  type="button"
                  class="btn btn-outline-secondary"
                  @click="resetForm"
                >
                  Cancelar
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <div class="col-lg-8">
        <div class="card shadow-sm">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h2 class="h5 mb-0">Lista de usuários</h2>

              <select
                v-model="filter"
                class="form-select form-select-sm w-auto"
                @change="loadUsers"
              >
                <option value="active">Ativos</option>
                <option value="with_trashed">Ativos + excluídos</option>
                <option value="only_trashed">Apenas excluídos</option>
              </select>
            </div>

            <div v-if="loading" class="text-muted">Carregando usuários...</div>

            <div v-else class="table-responsive">
              <table class="table table-striped align-middle">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Status</th>
                    <th class="text-end">Ações</th>
                  </tr>
                </thead>

                <tbody>
                  <tr v-for="user in users" :key="user.id">
                    <td>{{ user.id }}</td>
                    <td>{{ user.name }}</td>
                    <td>{{ user.email }}</td>
                    <td>
                      <span v-if="user.deleted_at" class="badge text-bg-danger"> Excluído </span>

                      <span v-else class="badge text-bg-success"> Ativo </span>
                    </td>
                    <td class="text-end">
                      <button
                        type="button"
                        class="btn btn-sm btn-outline-primary me-2"
                        :disabled="Boolean(user.deleted_at)"
                        @click="editUser(user)"
                      >
                        Editar
                      </button>

                      <button
                        type="button"
                        class="btn btn-sm btn-outline-danger"
                        :disabled="Boolean(user.deleted_at)"
                        @click="handleDelete(user)"
                      >
                        Excluir
                      </button>
                    </td>
                  </tr>

                  <tr v-if="users.length === 0">
                    <td colspan="5" class="text-center text-muted">Nenhum usuário encontrado.</td>
                  </tr>
                </tbody>
              </table>
            </div>

            <p class="text-muted small mt-3 mb-0">
              Registros excluídos usam Soft Delete e permanecem no banco com
              <code>deleted_at</code> preenchido.
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
