<script setup>
import { onMounted, reactive, ref } from 'vue'

import BaseAlert from '@/components/BaseAlert.vue'
import {
  createProfile,
  deleteProfile,
  listProfiles,
  updateProfile,
} from '@/services/profileService'

const profiles = ref([])
const loading = ref(false)
const successMessage = ref('')
const errorMessage = ref('')
const filter = ref('active')

const form = reactive({
  id: null,
  name: '',
})

function resetMessages() {
  successMessage.value = ''
  errorMessage.value = ''
}

function resetForm() {
  form.id = null
  form.name = ''
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

async function loadProfiles() {
  loading.value = true
  resetMessages()

  try {
    const response = await listProfiles(getFilterParams())
    profiles.value = response.data
  } catch (error) {
    errorMessage.value = error.response?.data?.message || 'Não foi possível carregar os perfis.'
  } finally {
    loading.value = false
  }
}

async function handleSubmit() {
  resetMessages()

  try {
    const payload = {
      name: form.name,
    }

    if (form.id) {
      await updateProfile(form.id, payload)
      successMessage.value = 'Perfil atualizado com sucesso.'
    } else {
      await createProfile(payload)
      successMessage.value = 'Perfil criado com sucesso.'
    }

    resetForm()
    await loadProfiles()
  } catch (error) {
    errorMessage.value =
      error.response?.data?.message ||
      Object.values(error.response?.data?.errors || {})?.[0]?.[0] ||
      'Não foi possível salvar o perfil.'
  }
}

function editProfile(profile) {
  resetMessages()

  form.id = profile.id
  form.name = profile.name
}

async function handleDelete(profile) {
  const confirmed = confirm(`Deseja excluir o perfil ${profile.name}?`)

  if (!confirmed) {
    return
  }

  resetMessages()

  try {
    await deleteProfile(profile.id)
    successMessage.value = 'Perfil excluído com sucesso.'
    await loadProfiles()
  } catch (error) {
    errorMessage.value = error.response?.data?.message || 'Não foi possível excluir o perfil.'
  }
}

onMounted(loadProfiles)
</script>

<template>
  <section>
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div>
        <h1 class="h3 mb-1">Perfis</h1>
        <p class="text-muted mb-0">Gerenciamento de perfis de acesso da aplicação.</p>
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
              {{ form.id ? 'Editar perfil' : 'Criar perfil' }}
            </h2>

            <form @submit.prevent="handleSubmit">
              <div class="mb-3">
                <label class="form-label" for="name">Perfil</label>
                <input id="name" v-model="form.name" type="text" class="form-control" required />
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

            <p class="text-muted small mt-3 mb-0">
              O perfil <strong>Administrador</strong> é criado automaticamente via seeder.
            </p>
          </div>
        </div>
      </div>

      <div class="col-lg-8">
        <div class="card shadow-sm">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h2 class="h5 mb-0">Lista de perfis</h2>

              <select
                v-model="filter"
                class="form-select form-select-sm w-auto"
                @change="loadProfiles"
              >
                <option value="active">Ativos</option>
                <option value="with_trashed">Ativos + excluídos</option>
                <option value="only_trashed">Apenas excluídos</option>
              </select>
            </div>

            <div v-if="loading" class="text-muted">Carregando perfis...</div>

            <div v-else class="table-responsive">
              <table class="table table-striped align-middle">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Perfil</th>
                    <th>Status</th>
                    <th class="text-end">Ações</th>
                  </tr>
                </thead>

                <tbody>
                  <tr v-for="profile in profiles" :key="profile.id">
                    <td>{{ profile.id }}</td>
                    <td>{{ profile.name }}</td>
                    <td>
                      <span v-if="profile.deleted_at" class="badge text-bg-danger"> Excluído </span>

                      <span v-else class="badge text-bg-success"> Ativo </span>
                    </td>
                    <td class="text-end">
                      <button
                        type="button"
                        class="btn btn-sm btn-outline-primary me-2"
                        :disabled="Boolean(profile.deleted_at)"
                        @click="editProfile(profile)"
                      >
                        Editar
                      </button>

                      <button
                        type="button"
                        class="btn btn-sm btn-outline-danger"
                        :disabled="Boolean(profile.deleted_at)"
                        @click="handleDelete(profile)"
                      >
                        Excluir
                      </button>
                    </td>
                  </tr>

                  <tr v-if="profiles.length === 0">
                    <td colspan="4" class="text-center text-muted">Nenhum perfil encontrado.</td>
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
