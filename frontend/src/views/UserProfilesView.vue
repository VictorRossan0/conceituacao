<script setup>
import { computed, onMounted, ref } from 'vue'

import BaseAlert from '@/components/BaseAlert.vue'
import { listProfiles } from '@/services/profileService'
import {
  attachProfileToUser,
  detachProfileFromUser,
  listUserProfiles,
} from '@/services/userProfileService'
import { listUsers } from '@/services/userService'

const users = ref([])
const profiles = ref([])
const userProfiles = ref([])

const selectedUserId = ref('')
const selectedProfileId = ref('')

const loading = ref(false)
const loadingUserProfiles = ref(false)

const successMessage = ref('')
const errorMessage = ref('')

const selectedUser = computed(() =>
  users.value.find((user) => String(user.id) === String(selectedUserId.value)),
)

const availableProfiles = computed(() => {
  const assignedProfileIds = userProfiles.value.map((profile) => profile.id)

  return profiles.value.filter(
    (profile) => !profile.deleted_at && !assignedProfileIds.includes(profile.id),
  )
})

function resetMessages() {
  successMessage.value = ''
  errorMessage.value = ''
}

async function loadInitialData() {
  loading.value = true
  resetMessages()

  try {
    const [usersResponse, profilesResponse] = await Promise.all([listUsers(), listProfiles()])

    users.value = usersResponse.data
    profiles.value = profilesResponse.data
  } catch (error) {
    errorMessage.value =
      error.response?.data?.message || 'Não foi possível carregar usuários e perfis.'
  } finally {
    loading.value = false
  }
}

async function loadProfilesFromSelectedUser() {
  userProfiles.value = []
  selectedProfileId.value = ''

  if (!selectedUserId.value) {
    return
  }

  loadingUserProfiles.value = true
  resetMessages()

  try {
    const response = await listUserProfiles(selectedUserId.value)
    userProfiles.value = response.data
  } catch (error) {
    errorMessage.value =
      error.response?.data?.message || 'Não foi possível carregar os perfis do usuário.'
  } finally {
    loadingUserProfiles.value = false
  }
}

async function handleAttachProfile() {
  resetMessages()

  if (!selectedUserId.value || !selectedProfileId.value) {
    errorMessage.value = 'Selecione um usuário e um perfil para associar.'
    return
  }

  try {
    await attachProfileToUser(selectedUserId.value, selectedProfileId.value)

    successMessage.value = 'Perfil associado ao usuário com sucesso.'
    selectedProfileId.value = ''

    await loadProfilesFromSelectedUser()
  } catch (error) {
    errorMessage.value =
      error.response?.data?.message || 'Não foi possível associar o perfil ao usuário.'
  }
}

async function handleDetachProfile(profile) {
  const confirmed = confirm(`Deseja desassociar o perfil ${profile.name} deste usuário?`)

  if (!confirmed) {
    return
  }

  resetMessages()

  try {
    await detachProfileFromUser(selectedUserId.value, profile.id)

    successMessage.value = 'Perfil desassociado do usuário com sucesso.'

    await loadProfilesFromSelectedUser()
  } catch (error) {
    errorMessage.value =
      error.response?.data?.message || 'Não foi possível desassociar o perfil do usuário.'
  }
}

onMounted(loadInitialData)
</script>

<template>
  <section>
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div>
        <h1 class="h3 mb-1">Associações usuário-perfis</h1>
        <p class="text-muted mb-0">Gerencie os perfis vinculados a cada usuário.</p>
      </div>

      <RouterLink class="btn btn-outline-secondary btn-sm" to="/dashboard"> Voltar </RouterLink>
    </div>

    <BaseAlert v-if="successMessage" type="success" :message="successMessage" />
    <BaseAlert v-if="errorMessage" type="danger" :message="errorMessage" />

    <div v-if="loading" class="text-muted">Carregando dados iniciais...</div>

    <div v-else class="row g-4">
      <div class="col-lg-5">
        <div class="card shadow-sm">
          <div class="card-body">
            <h2 class="h5 mb-3">Selecionar usuário</h2>

            <div class="mb-3">
              <label class="form-label" for="user">Usuário</label>
              <select
                id="user"
                v-model="selectedUserId"
                class="form-select"
                @change="loadProfilesFromSelectedUser"
              >
                <option value="">Selecione um usuário</option>

                <option v-for="user in users" :key="user.id" :value="user.id">
                  #{{ user.id }} - {{ user.name }} ({{ user.email }})
                </option>
              </select>
            </div>

            <div v-if="selectedUser" class="border rounded p-3 bg-light">
              <p class="mb-1">
                <strong>Usuário selecionado:</strong>
                {{ selectedUser.name }}
              </p>

              <p class="mb-0">
                <strong>E-mail:</strong>
                {{ selectedUser.email }}
              </p>
            </div>
          </div>
        </div>

        <div class="card shadow-sm mt-4">
          <div class="card-body">
            <h2 class="h5 mb-3">Associar perfil</h2>

            <div class="mb-3">
              <label class="form-label" for="profile">Perfil disponível</label>
              <select
                id="profile"
                v-model="selectedProfileId"
                class="form-select"
                :disabled="!selectedUserId"
              >
                <option value="">Selecione um perfil</option>

                <option v-for="profile in availableProfiles" :key="profile.id" :value="profile.id">
                  #{{ profile.id }} - {{ profile.name }}
                </option>
              </select>

              <div v-if="selectedUserId && availableProfiles.length === 0" class="form-text">
                Todos os perfis ativos já estão associados a este usuário.
              </div>
            </div>

            <button
              type="button"
              class="btn btn-primary"
              :disabled="!selectedUserId || !selectedProfileId"
              @click="handleAttachProfile"
            >
              Associar perfil
            </button>
          </div>
        </div>
      </div>

      <div class="col-lg-7">
        <div class="card shadow-sm">
          <div class="card-body">
            <h2 class="h5 mb-3">Perfis vinculados</h2>

            <div v-if="!selectedUserId" class="text-muted">
              Selecione um usuário para visualizar os perfis vinculados.
            </div>

            <div v-else-if="loadingUserProfiles" class="text-muted">
              Carregando perfis do usuário...
            </div>

            <div v-else class="table-responsive">
              <table class="table table-striped align-middle">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Perfil</th>
                    <th class="text-end">Ações</th>
                  </tr>
                </thead>

                <tbody>
                  <tr v-for="profile in userProfiles" :key="profile.id">
                    <td>{{ profile.id }}</td>
                    <td>{{ profile.name }}</td>
                    <td class="text-end">
                      <button
                        type="button"
                        class="btn btn-sm btn-outline-danger"
                        @click="handleDetachProfile(profile)"
                      >
                        Desassociar
                      </button>
                    </td>
                  </tr>

                  <tr v-if="userProfiles.length === 0">
                    <td colspan="3" class="text-center text-muted">
                      Nenhum perfil vinculado a este usuário.
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <p class="text-muted small mt-3 mb-0">
              A associação utiliza relacionamento muitos-para-muitos entre usuários e perfis através
              da tabela pivot <code>profile_user</code>.
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
