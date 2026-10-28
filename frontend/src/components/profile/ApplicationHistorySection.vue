<script setup lang="ts">
import { ref } from 'vue'
import { backendAssetUrl, type Application, type ApplicationStatus } from '@/services/api'
import UserAvatar from '@/components/profile/UserAvatar.vue'

defineProps<{
  applications: Application[]
  canReview?: boolean
  updatingId?: Application['id'] | null
}>()

const expandedApplicationId = ref<Application['id'] | null>(null)

defineEmits<{
  statusChange: [application: Application, status: ApplicationStatus]
  withdraw: [application: Application]
}>()

function statusLabel(status: ApplicationStatus) {
  return {
    waiting: 'Menunggu',
    accepted: 'Diterima',
    rejected: 'Ditolak',
    cancelled: 'Dibatalkan',
    completed: 'Selesai',
  }[status]
}

function genderLabel(gender?: string | null) {
  return gender === 'female' ? 'Perempuan' : gender === 'male' ? 'Laki-laki' : 'Belum diisi'
}

function studentStatusLabel(status?: string | null) {
  return {
    active: 'Aktif',
    inactive: 'Tidak aktif',
    graduated: 'Lulus',
    dropped_out: 'Putus studi',
  }[status ?? ''] ?? 'Belum diisi'
}
</script>

<template>
  <section class="mt-9">
    <div class="mb-4">
      <h2 class="m-0 text-lg font-semibold">Riwayat lamaran magang</h2>
      <p class="mb-0 mt-1 text-xs text-[#75817b]">Pantau status setiap pengajuan magangmu.</p>
    </div>
    <div v-if="applications.length" class="grid gap-3">
      <article
        v-for="application in applications"
        :key="application.id"
        class="flex flex-wrap items-center gap-3 rounded-xl border border-[#edf0ed] p-4"
      >
        <UserAvatar
          v-if="canReview"
          :name="application.student?.name || 'Mahasiswa'"
          :profile-picture="application.student?.profile_picture"
        />
        <span v-else class="grid size-10 shrink-0 place-items-center rounded-xl bg-[#e5f3e9] font-bold text-[#196b4a]">
          {{ application.internship?.title?.slice(0, 1) || 'M' }}
        </span>
        <div class="min-w-0 flex-1">
          <h3 class="m-0 truncate text-sm font-semibold">{{ canReview ? application.student?.name || 'Mahasiswa' : application.internship?.title || `Magang ${application.internship_id}` }}</h3>
          <p class="mb-0 mt-1 text-[11px] text-[#75817b]">
            <template v-if="canReview">{{ application.internship?.title || `Magang ${application.internship_id}` }} · {{ application.student?.email || 'Email belum tersedia' }}</template>
            <template v-else>Diajukan</template>
            {{ new Date(application.created_at).toLocaleDateString('id-ID') }}
          </p>
          <a v-if="canReview && application.cv" class="mt-1 inline-block text-[10px] font-semibold text-[#196b4a]" :href="backendAssetUrl(application.cv)" target="_blank" rel="noreferrer">Lihat CV</a>
          <button
            v-if="canReview"
            class="mt-2 block text-[10px] font-bold text-[#196b4a] underline underline-offset-2"
            type="button"
            :aria-expanded="expandedApplicationId === application.id"
            @click="expandedApplicationId = expandedApplicationId === application.id ? null : application.id"
          >{{ expandedApplicationId === application.id ? 'Tutup detail mahasiswa' : 'Lihat detail mahasiswa' }}</button>
        </div>
        <span
          class="rounded-full bg-[#fff2d9] px-2.5 py-1.5 text-[10px] font-bold text-[#b77c18]"
          :class="{
            '!bg-[#e5f3e9] !text-[#408258]': application.status === 'accepted' || application.status === 'completed',
            '!bg-[#ffefed] !text-[#a94e47]': application.status === 'rejected' || application.status === 'cancelled',
          }"
        >
          {{ statusLabel(application.status) }}
        </span>
        <div
          v-if="canReview && application.status === 'waiting'"
          class="flex w-full flex-wrap justify-end gap-2"
        >
          <button
            class="rounded-md border border-[#e5f3e9] bg-[#e5f3e9] px-3 py-2 text-[10px] font-bold text-[#28744e] disabled:opacity-50"
            type="button"
            :disabled="updatingId === application.id"
            @click="$emit('statusChange', application, 'accepted')"
          >Terima</button>
          <button
            class="rounded-md border border-[#ffefed] bg-[#ffefed] px-3 py-2 text-[10px] font-bold text-[#a94e47] disabled:opacity-50"
            type="button"
            :disabled="updatingId === application.id"
            @click="$emit('statusChange', application, 'rejected')"
          >Tolak</button>
          <button
            class="rounded-md border border-[#edf0ed] bg-white px-3 py-2 text-[10px] font-bold text-[#75817b] disabled:opacity-50"
            type="button"
            :disabled="updatingId === application.id"
            @click="$emit('statusChange', application, 'cancelled')"
          >Batalkan</button>
        </div>
        <div v-else-if="!canReview && application.status === 'waiting'" class="flex w-full justify-end">
          <button
            class="rounded-md border border-[#ffefed] bg-[#ffefed] px-3 py-2 text-[10px] font-bold text-[#a94e47] disabled:opacity-50"
            type="button"
            :disabled="updatingId === application.id"
            @click="$emit('withdraw', application)"
          >{{ updatingId === application.id ? 'Memproses...' : 'Mengundurkan diri' }}</button>
        </div>
        <section
          v-if="canReview && expandedApplicationId === application.id"
          class="grid w-full gap-3 rounded-lg bg-[#f7faf6] p-4 sm:grid-cols-2"
          :aria-label="`Detail mahasiswa ${application.student?.name || ''}`"
        >
          <div>
            <p class="m-0 text-[10px] font-bold uppercase tracking-wide text-[#75817b]">NIM</p>
            <p class="mb-0 mt-1 text-xs font-semibold">{{ application.student?.student_profile?.nim || 'Belum diisi' }}</p>
          </div>
          <div>
            <p class="m-0 text-[10px] font-bold uppercase tracking-wide text-[#75817b]">Program studi</p>
            <p class="mb-0 mt-1 text-xs font-semibold">{{ application.student?.student_profile?.major || 'Belum diisi' }}</p>
          </div>
          <div>
            <p class="m-0 text-[10px] font-bold uppercase tracking-wide text-[#75817b]">Nomor telepon</p>
            <p class="mb-0 mt-1 text-xs font-semibold">{{ application.student?.phone || 'Belum diisi' }}</p>
          </div>
          <div>
            <p class="m-0 text-[10px] font-bold uppercase tracking-wide text-[#75817b]">Jenis kelamin</p>
            <p class="mb-0 mt-1 text-xs font-semibold">{{ genderLabel(application.student?.student_profile?.gender) }}</p>
          </div>
          <div>
            <p class="m-0 text-[10px] font-bold uppercase tracking-wide text-[#75817b]">Tahun masuk</p>
            <p class="mb-0 mt-1 text-xs font-semibold">{{ application.student?.student_profile?.enrollment_year || 'Belum diisi' }}</p>
          </div>
          <div>
            <p class="m-0 text-[10px] font-bold uppercase tracking-wide text-[#75817b]">Status mahasiswa</p>
            <p class="mb-0 mt-1 text-xs font-semibold">{{ studentStatusLabel(application.student?.student_profile?.status) }}</p>
          </div>
        </section>
      </article>
    </div>
    <p v-else class="rounded-xl border border-dashed border-[#e1e9e2] px-5 py-8 text-center text-xs text-[#75817b]">
      {{ canReview ? 'Belum ada mahasiswa yang mengajukan magang.' : 'Kamu belum mengajukan lamaran magang.' }}
    </p>
  </section>
</template>
