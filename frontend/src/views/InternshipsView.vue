<script setup lang="ts">
import { computed, onMounted, ref } from 'vue'
import { RouterLink } from 'vue-router'
import AppLayout from '@/components/AppLayout.vue'
import InternshipsHeading from '@/components/internships/InternshipsHeading.vue'
import InternshipCard from '@/components/internships/InternshipCard.vue'
import { apiRequest, unwrapData, type ApiEnvelope, type Application, type Internship } from '@/services/api'
import { useAuthStore } from '@/stores/auth'

const auth = useAuthStore()
const internships = ref<Internship[]>([])
const appliedIds = ref(new Set<string>())
const search = ref('')
const selectedInternship = ref<Internship | null>(null)
const selectedCv = ref<File | null>(null)
const loading = ref(true)
const errorMessage = ref('')
const notice = ref('')
const applyingId = ref<string | null>(null)

const filteredInternships = computed(() => {
  const query = search.value.trim().toLocaleLowerCase('id')
  if (!query) return internships.value
  return internships.value.filter((internship) =>
    [internship.title, internship.description, internship.campus_unit_id]
      .join(' ')
      .toLocaleLowerCase('id')
      .includes(query),
  )
})

async function loadInternships() {
  loading.value = true
  errorMessage.value = ''
  try {
    const response = await apiRequest<ApiEnvelope<Internship[]>>('/internships')
    internships.value = unwrapData<Internship[]>(response).filter((item) => item.is_open)
  } catch (error) {
    errorMessage.value =
      error instanceof Error ? error.message : 'Lowongan belum dapat dimuat. Coba lagi.'
  } finally {
    loading.value = false
  }
}

async function loadApplications() {
  if (auth.user?.role !== 'student') return
  try {
    const response = await apiRequest<ApiEnvelope<Application[]>>('/intern-application/student', {}, auth.token)
    appliedIds.value = new Set(
      unwrapData<Application[]>(response).map((application) => application.internship_id),
    )
  } catch (error) {
    errorMessage.value =
      error instanceof Error ? error.message : 'Riwayat pendaftaran belum dapat dimuat.'
  }
}

function showDetails(id: string) {
  selectedCv.value = null
  selectedInternship.value = internships.value.find((item) => item.id === id) ?? null
}

async function apply(internship: Internship) {
  if (auth.user?.role !== 'student') return
  selectedCv.value = null
  selectedInternship.value = internship
}

async function submitApplication() {
  const internship = selectedInternship.value
  if (auth.user?.role !== 'student' || !internship) return

  const cv = selectedCv.value
  if (internship.cv_required && !cv) {
    errorMessage.value = 'Unggah CV terlebih dahulu untuk melamar lowongan ini.'
    return
  }

  applyingId.value = internship.id
  notice.value = ''
  errorMessage.value = ''
  try {
    const body = new FormData()
    body.set('internship_id', internship.id)
    if (cv) body.set('cv', cv)

    await apiRequest(
      '/intern-application/student',
      {
        method: 'POST',
        body,
      },
      auth.token,
    )
    appliedIds.value = new Set([...appliedIds.value, internship.id])
    selectedInternship.value = null
    notice.value = 'Lamaran berhasil dikirim. Pantau statusnya dari dashboard.'
  } catch (error) {
    errorMessage.value =
      error instanceof Error ? error.message : 'Pendaftaran gagal dikirim. Coba lagi.'
  } finally {
    applyingId.value = null
  }
}

function updateSelectedCv(event: Event) {
  const input = event.currentTarget
  if (input instanceof HTMLInputElement) {
    selectedCv.value = input.files?.[0] ?? null
    errorMessage.value = ''
  }
}

onMounted(async () => {
  await Promise.all([loadInternships(), loadApplications()])
})
</script>

<template>
  <AppLayout>
    <InternshipsHeading v-model="search" />
    <p v-if="notice" class="mb-4 rounded-lg bg-[#e8f4eb] p-3 text-xs text-[#28744e]" role="status">{{ notice }}</p>
    <p v-if="errorMessage" class="mb-4 rounded-lg bg-[#fff0ed] p-3 text-xs text-[#a64336]" role="alert">{{ errorMessage }}</p>

    <div class="flex min-h-[58px] items-center justify-between gap-4 border-y border-[#edf0ed]">
      <strong class="text-[13px]">{{ filteredInternships.length }} peluang tersedia</strong>
      <RouterLink v-if="auth.user?.role === 'campus-unit'" class="button button-small" to="/dashboard">
        Kelola magang
      </RouterLink>
      <RouterLink v-else-if="!auth.user" class="text-xs font-bold text-[#196b4a] no-underline" to="/register">
        Daftar sebagai mahasiswa <span aria-hidden="true">→</span>
      </RouterLink>
    </div>

    <p v-if="loading" class="py-16 text-center text-xs text-[#75817b]">Memuat lowongan...</p>
    <div v-else-if="filteredInternships.length" class="grid grid-cols-3 gap-[17px] pt-6 max-[900px]:grid-cols-2 max-[620px]:grid-cols-1">
      <InternshipCard
        v-for="internship in filteredInternships"
        :key="internship.id"
        :internship="internship"
        :is-student="auth.user?.role === 'student'"
        :is-applied="appliedIds.has(internship.id)"
        :applying="applyingId === internship.id"
        @details="showDetails"
        @apply="apply"
      />
    </div>
    <div v-else-if="!loading" class="grid justify-items-center py-20 text-center">
      <span class="text-4xl text-[#82aa8e]" aria-hidden="true">⌕</span>
      <h2 class="mb-1 mt-3 text-xl">{{ search ? 'Belum ada hasil yang cocok' : 'Belum ada lowongan aktif' }}</h2>
      <p class="m-0 max-w-[440px] text-[13px] leading-7 text-[#75817b]">
        {{ search ? 'Coba kata kunci lain untuk menemukan peluangmu.' : 'Peluang baru akan muncul saat unit kampus membuka pendaftaran.' }}
      </p>
    </div>

    <div v-if="selectedInternship" class="fixed inset-0 z-10 grid place-items-center bg-[#10251bcc] p-5" @click.self="selectedInternship = null">
      <section class="relative max-h-[90vh] w-[min(570px,100%)] overflow-y-auto rounded-2xl bg-white p-8 shadow-2xl max-[620px]:p-6" role="dialog" aria-modal="true" :aria-label="selectedInternship.title">
        <button class="absolute right-4 top-4 grid size-9 place-items-center rounded-full border border-[#edf0ed] bg-white text-xl text-[#526059]" type="button" aria-label="Tutup" @click="selectedInternship = null; selectedCv = null">×</button>
        <span class="section-label">Unit kampus</span>
        <h2 class="mb-2 mt-3 mr-8 text-2xl font-semibold tracking-tight">{{ selectedInternship.title }}</h2>
        <p class="whitespace-pre-line text-[13px] leading-7 text-[#75817b]">{{ selectedInternship.description }}</p>
        <div class="my-5 grid grid-cols-3 gap-3 border-y border-[#edf0ed] py-4 text-[11px] max-[620px]:grid-cols-1">
          <span><b class="mb-1 block text-[9px] text-[#89938c]">Mulai</b>{{ new Date(selectedInternship.start_date).toLocaleDateString('id-ID') }}</span>
          <span><b class="mb-1 block text-[9px] text-[#89938c]">Selesai</b>{{ new Date(selectedInternship.end_date).toLocaleDateString('id-ID') }}</span>
          <span><b class="mb-1 block text-[9px] text-[#89938c]">Kuota</b>{{ selectedInternship.quota }} mahasiswa</span>
        </div>
        <p v-if="selectedInternship.cv_required" class="mb-3 text-[11px] font-semibold text-[#196b4a]">CV wajib dilampirkan (PDF, DOC, atau DOCX, maks. 2 MB).</p>
        <label
          v-if="auth.user?.role === 'student'"
          class="mb-4 grid gap-2 text-[11px] font-semibold text-[#3a4941]"
        >
          <span>{{ selectedInternship.cv_required ? 'Unggah CV *' : 'Unggah CV (opsional)' }}</span>
          <input
            id="application-cv"
            class="rounded-lg border border-[#e3e9e4] p-3 text-[11px] font-normal"
            type="file"
            accept=".pdf,.doc,.docx"
            :required="selectedInternship.cv_required"
            @change="updateSelectedCv"
          />
        </label>
        <p v-if="errorMessage" class="mb-3 text-[11px] text-[#b54242]" role="alert">{{ errorMessage }}</p>
        <button
          v-if="auth.user?.role === 'student' && (!selectedInternship.cv_required || selectedCv)"
          class="button w-full bg-[#196b4a] text-white hover:bg-[#145a3e] disabled:cursor-not-allowed disabled:opacity-50"
          type="button"
          :disabled="appliedIds.has(selectedInternship.id) || applyingId === selectedInternship.id"
          @click="submitApplication"
        >{{ appliedIds.has(selectedInternship.id) ? 'Sudah mengajukan' : applyingId === selectedInternship.id ? 'Mengirim...' : 'Ajukan sekarang' }}</button>
        <RouterLink v-else-if="!auth.user" class="button mt-4 block text-center no-underline" to="/login">Masuk untuk mendaftar</RouterLink>
      </section>
    </div>
  </AppLayout>
</template>

<style>
.button {
  background: #196b4a;
  color: #fff;
}
.button:hover {
  background: #145a3e;
}
</style>
