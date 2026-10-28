<script setup lang="ts">
import { reactive, ref, watch } from 'vue'
import type { Internship, InternshipUpdate } from '@/services/api'

const props = defineProps<{
  internships: Internship[]
  savingId: Internship['id'] | null
  savedId: Internship['id'] | null
}>()

const emit = defineEmits<{
  update: [id: Internship['id'], payload: InternshipUpdate]
  'toggle-status': [id: Internship['id'], isOpen: boolean]
}>()

const editingId = ref<Internship['id'] | null>(null)
const form = reactive<InternshipUpdate>({
  title: '',
  description: '',
  start_date: '',
  end_date: '',
  quota: 1,
  cv_required: false,
})

function beginEdit(internship: Internship) {
  editingId.value = internship.id
  form.title = internship.title
  form.description = internship.description
  form.start_date = internship.start_date.slice(0, 10)
  form.end_date = internship.end_date.slice(0, 10)
  form.quota = internship.quota
  form.cv_required = internship.cv_required
}

function submitEdit() {
  if (!editingId.value) return
  emit('update', editingId.value, { ...form })
}

watch(() => props.savedId, (id) => {
  if (id && id === editingId.value) editingId.value = null
})

watch(
  () => props.internships.find((internship) => internship.id === editingId.value)?.is_open,
  (isOpen) => {
    if (isOpen === false) editingId.value = null
  },
)
</script>

<template>
  <section class="mt-9">
    <div class="mb-4">
      <h2 class="m-0 text-lg font-semibold">Riwayat magang yang dibuat</h2>
      <p class="mb-0 mt-1 text-xs text-[#75817b]">Lowongan yang masih dibuka bisa diedit oleh unit pembuatnya.</p>
    </div>
    <div v-if="internships.length" class="grid gap-3">
      <article
        v-for="internship in internships"
        :key="internship.id"
        class="rounded-xl border border-[#edf0ed] p-4"
      >
        <div class="flex items-center gap-3">
          <span class="grid size-10 shrink-0 place-items-center rounded-xl bg-[#e5f3e9] font-bold text-[#196b4a]">
            {{ internship.title.slice(0, 1) }}
          </span>
          <div class="min-w-0 flex-1">
            <h3 class="m-0 truncate text-sm font-semibold">{{ internship.title }}</h3>
            <p class="mb-0 mt-1 text-[11px] text-[#75817b]">
              {{ new Date(internship.start_date).toLocaleDateString('id-ID') }} –
              {{ new Date(internship.end_date).toLocaleDateString('id-ID') }}
              · {{ internship.quota }} posisi
            </p>
          </div>
          <span
            class="rounded-full px-2.5 py-1.5 text-[10px] font-bold"
            :class="internship.is_open ? 'bg-[#e5f3e9] text-[#408258]' : 'bg-[#f1f2f1] text-[#75817b]'"
          >
            {{ internship.is_open ? 'Dibuka' : 'Ditutup' }}
          </span>
          <button
            class="rounded-lg border border-[#dce7de] px-3 py-2 text-[11px] font-semibold text-[#196b4a] hover:bg-[#f4f8f4] disabled:cursor-wait disabled:opacity-60"
            type="button"
            :disabled="props.savingId === internship.id"
            @click="emit('toggle-status', internship.id, !internship.is_open)"
          >
            {{
              props.savingId === internship.id
                ? 'Menyimpan...'
                : internship.is_open
                  ? 'Tutup lamaran'
                  : 'Buka lamaran'
            }}
          </button>
          <button
            v-if="internship.is_open"
            :disabled="props.savingId === internship.id"
            class="rounded-lg border border-[#dce7de] px-3 py-2 text-[11px] font-semibold text-[#196b4a] hover:bg-[#f4f8f4]"
            type="button"
            @click="editingId === internship.id ? editingId = null : beginEdit(internship)"
          >
            {{ editingId === internship.id ? 'Batal' : 'Edit' }}
          </button>
        </div>
        <form v-if="internship.is_open && editingId === internship.id" class="mt-4 grid gap-4 border-t border-[#edf0ed] pt-4" @submit.prevent="submitEdit">
          <label class="grid gap-1.5 text-[11px] font-bold text-[#3a4941]">
            Judul lowongan
            <input v-model.trim="form.title" class="min-h-[42px] rounded-lg border border-[#e3e9e4] px-3 font-normal outline-none focus:border-[#196b4a]" required maxlength="255" />
          </label>
          <label class="grid gap-1.5 text-[11px] font-bold text-[#3a4941]">
            Deskripsi
            <textarea v-model.trim="form.description" class="min-h-24 rounded-lg border border-[#e3e9e4] px-3 py-2 font-normal outline-none focus:border-[#196b4a]" required />
          </label>
          <div class="grid grid-cols-3 gap-3 max-[620px]:grid-cols-1">
            <label class="grid gap-1.5 text-[11px] font-bold text-[#3a4941]">Tanggal mulai<input v-model="form.start_date" class="min-h-[42px] rounded-lg border border-[#e3e9e4] px-3 font-normal" type="date" required /></label>
            <label class="grid gap-1.5 text-[11px] font-bold text-[#3a4941]">Tanggal selesai<input v-model="form.end_date" class="min-h-[42px] rounded-lg border border-[#e3e9e4] px-3 font-normal" type="date" :min="form.start_date" required /></label>
            <label class="grid gap-1.5 text-[11px] font-bold text-[#3a4941]">Kuota mahasiswa<input v-model.number="form.quota" class="min-h-[42px] rounded-lg border border-[#e3e9e4] px-3 font-normal" type="number" min="1" required /></label>
          </div>
          <label class="flex items-start gap-2 text-[11px] font-medium text-[#3a4941]">
            <input v-model="form.cv_required" class="mt-0.5 accent-[#196b4a]" type="checkbox" />
            Wajibkan mahasiswa mengunggah CV saat mengajukan lamaran
          </label>
          <button
            class="button justify-self-start disabled:cursor-wait disabled:opacity-60"
            type="submit"
            :disabled="props.savingId === internship.id"
          >
            {{ props.savingId === internship.id ? 'Menyimpan...' : 'Simpan perubahan' }}
          </button>
        </form>
      </article>
    </div>
    <p v-else class="rounded-xl border border-dashed border-[#e1e9e2] px-5 py-8 text-center text-xs text-[#75817b]">
      Unitmu belum memiliki riwayat lowongan magang.
    </p>
  </section>
</template>
