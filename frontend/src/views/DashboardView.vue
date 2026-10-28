<script setup lang="ts">
import { computed, onMounted, ref } from 'vue'
import { RouterLink } from 'vue-router'
import AppLayout from '@/components/AppLayout.vue'
import DashboardHeading from '@/components/dashboard/DashboardHeading.vue'
import DivisionStats from '@/components/dashboard/DivisionStats.vue'
import StudentSummary from '@/components/dashboard/StudentSummary.vue'
import InternshipCreateSection from '@/components/dashboard/InternshipCreateSection.vue'
import ApplicationHistorySection from '@/components/profile/ApplicationHistorySection.vue'
import InternshipHistorySection from '@/components/profile/InternshipHistorySection.vue'
import { apiRequest, unwrapData, type ApiEnvelope, type Application, type ApplicationStatus, type Internship, type InternshipUpdate } from '@/services/api'
import { useAuthStore } from '@/stores/auth'

const auth = useAuthStore()
const isCampusUnit = computed(() => auth.user?.role === 'campus-unit')
const internships = ref<Internship[]>([])
const applications = ref<Application[]>([])
const loading = ref(true)
const savingInternship = ref(false)
const savingInternshipId = ref<Internship['id'] | null>(null)
const savedInternshipId = ref<Internship['id'] | null>(null)
const updatingApplicationId = ref<Application['id'] | null>(null)
const errorMessage = ref('')
const notice = ref('')
const formResetKey = ref(0)

async function loadDashboard() {
  loading.value = true
  errorMessage.value = ''
  try {
    if (isCampusUnit.value && auth.user) {
      const [internshipResponse, applicationResponse] = await Promise.all([
        apiRequest<ApiEnvelope<Internship[]>>(`/internships?campus_unit_id=${encodeURIComponent(auth.user.id)}`, {}, auth.token),
        apiRequest<ApiEnvelope<Application[]>>('/intern-application/campus-unit', {}, auth.token),
      ])
      internships.value = unwrapData<Internship[]>(internshipResponse)
      applications.value = unwrapData<Application[]>(applicationResponse)
    } else {
      const response = await apiRequest<ApiEnvelope<Application[]>>('/intern-application/student', {}, auth.token)
      applications.value = unwrapData<Application[]>(response)
    }
  } catch (error) {
    errorMessage.value =
      error instanceof Error ? error.message : 'Dashboard belum dapat dimuat. Coba lagi.'
  } finally {
    loading.value = false
  }
}

async function createInternship(payload: {
  title: string
  description: string
  start_date: string
  end_date: string
  quota: number
  cv_required: boolean
}) {
  savingInternship.value = true
  errorMessage.value = ''
  notice.value = ''
  try {
    await apiRequest(
      '/internships',
      {
        method: 'POST',
        body: JSON.stringify({ ...payload, campus_unit_id: auth.user?.id, is_open: true }),
      },
      auth.token,
    )
    notice.value = 'Lowongan magang berhasil dipublikasikan.'
    formResetKey.value += 1
    await loadDashboard()
  } catch (error) {
    errorMessage.value = error instanceof Error ? error.message : 'Lowongan belum dapat disimpan.'
  } finally {
    savingInternship.value = false
  }
}

async function updateInternship(id: Internship['id'], payload: InternshipUpdate) {
  savingInternshipId.value = id
  savedInternshipId.value = null
  errorMessage.value = ''
  notice.value = ''
  try {
    const response = await apiRequest<ApiEnvelope<Internship>>(
      `/internships?id=${encodeURIComponent(id)}`,
      { method: 'PUT', body: JSON.stringify(payload) },
      auth.token,
    )
    const updatedInternship = unwrapData<Internship>(response)
    internships.value = internships.value.map((internship) =>
      internship.id === id ? updatedInternship : internship,
    )
    savedInternshipId.value = id
    notice.value = 'Lowongan magang berhasil diperbarui.'
  } catch (error) {
    errorMessage.value = error instanceof Error ? error.message : 'Lowongan gagal diperbarui.'
  } finally {
    savingInternshipId.value = null
  }
}

async function toggleInternshipStatus(id: Internship['id'], isOpen: boolean) {
  savingInternshipId.value = id
  errorMessage.value = ''
  notice.value = ''
  try {
    const response = await apiRequest<ApiEnvelope<Internship>>(
      `/internships?id=${encodeURIComponent(id)}`,
      { method: 'PUT', body: JSON.stringify({ is_open: isOpen }) },
      auth.token,
    )
    const updatedInternship = unwrapData<Internship>(response)
    internships.value = internships.value.map((internship) =>
      internship.id === id ? updatedInternship : internship,
    )
    notice.value = `Lamaran berhasil ${updatedInternship.is_open ? 'dibuka' : 'ditutup'}.`
  } catch (error) {
    errorMessage.value = error instanceof Error ? error.message : 'Status lamaran gagal diperbarui.'
  } finally {
    savingInternshipId.value = null
  }
}

async function updateStatus(application: Application, status: ApplicationStatus) {
  updatingApplicationId.value = application.id
  errorMessage.value = ''
  notice.value = ''
  try {
    await apiRequest(
      `/intern-application/${isCampusUnit.value ? 'campus-unit' : 'student'}`,
      { method: 'PUT', body: JSON.stringify({ id: application.id, status }) },
      auth.token,
    )
    applications.value = applications.value.map((item) =>
      item.id === application.id ? { ...item, status } : item,
    )
    notice.value = isCampusUnit.value
      ? `Status lamaran berhasil diubah menjadi ${status}.`
      : 'Kamu berhasil mengundurkan diri dari lamaran ini.'
  } catch (error) {
    errorMessage.value = error instanceof Error ? error.message : 'Status lamaran gagal diperbarui.'
  } finally {
    updatingApplicationId.value = null
  }
}

onMounted(loadDashboard)
</script>

<template>
  <AppLayout>
    <DashboardHeading :is-division="isCampusUnit" :name="auth.user?.name ?? ''" />
    <p v-if="notice" class="mb-4 rounded-lg bg-[#e8f4eb] p-3 text-xs text-[#28744e]" role="status">{{ notice }}</p>
    <p v-if="errorMessage" class="mb-4 rounded-lg bg-[#fff0ed] p-3 text-xs text-[#a64336]" role="alert">{{ errorMessage }}</p>
    <p v-if="loading" class="py-16 text-center text-xs text-[#75817b]">Memuat dashboard...</p>

    <template v-else-if="isCampusUnit">
      <DivisionStats :active-internships="internships.filter((item) => item.is_open).length" :applicants="applications.length" />
      <InternshipCreateSection :reset-key="formResetKey" :saving="savingInternship" @create="createInternship" />
      <InternshipHistorySection
        :internships="internships"
        :saving-id="savingInternshipId"
        :saved-id="savedInternshipId"
        @update="updateInternship"
        @toggle-status="toggleInternshipStatus"
      />
      <ApplicationHistorySection
        class="mt-9"
        :applications="applications"
        can-review
        :updating-id="updatingApplicationId"
        @status-change="updateStatus"
      />
    </template>

    <template v-else>
      <StudentSummary
        :applications="applications.length"
        :accepted="applications.filter((item) => item.status === 'accepted').length"
      />
      <section class="rounded-[15px] border border-[#edf0ed] p-6">
        <h2 class="m-0 text-lg font-semibold">Data dirimu</h2>
        <p class="mb-4 mt-2 text-xs leading-6 text-[#75817b]">
          Lengkapi informasi mahasiswa agar unit kampus dapat meninjau pengajuanmu.
        </p>
        <RouterLink class="button inline-flex no-underline" to="/profil">Lengkapi profil</RouterLink>
      </section>
      <ApplicationHistorySection
        :applications="applications"
        :updating-id="updatingApplicationId"
        @withdraw="updateStatus($event, 'cancelled')"
      />
    </template>
  </AppLayout>
</template>
