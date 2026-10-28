<script setup lang="ts">
import { reactive, watch } from 'vue'

const props = defineProps<{
  saving: boolean
  resetKey: number
}>()

const emit = defineEmits<{
  create: [payload: {
    title: string
    description: string
    start_date: string
    end_date: string
    quota: number
    cv_required: boolean
  }]
}>()

const form = reactive({
  title: '',
  description: '',
  start_date: '',
  end_date: '',
  quota: 1,
  cv_required: false,
})

function submit() {
  emit('create', { ...form })
}

function clearForm() {
  form.title = ''
  form.description = ''
  form.start_date = ''
  form.end_date = ''
  form.quota = 1
  form.cv_required = false
}

watch(() => props.resetKey, clearForm)
</script>

<template>
  <section class="mb-9 rounded-[15px] border border-[#edf0ed] bg-white p-6">
    <div class="mb-5">
      <h2 class="m-0 text-lg font-semibold">Publikasikan kesempatan magang</h2>
      <p class="mb-0 mt-1 text-xs text-[#75817b]">Buat lowongan baru untuk mahasiswa.</p>
    </div>
    <form class="grid gap-4" @submit.prevent="submit">
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
      <button class="button justify-self-start disabled:cursor-wait disabled:opacity-60" type="submit" :disabled="saving">
        {{ saving ? 'Menyimpan...' : 'Publikasikan lowongan' }}
      </button>
    </form>
  </section>
</template>
