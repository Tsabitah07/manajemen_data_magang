<script setup lang="ts">
import type { Internship } from '@/services/api'

defineProps<{
  internship: Internship
  isStudent: boolean
  isApplied: boolean
  applying: boolean
}>()

defineEmits<{
  details: [id: string]
  apply: [internship: Internship]
}>()
</script>

<template>
  <article class="flex min-h-[310px] flex-col rounded-[13px] border border-[#edf0ed] bg-white p-5 transition hover:-translate-y-[3px] hover:shadow-[0_15px_35px_#244c360d]">
    <div class="flex items-center justify-between gap-2">
      <span class="grid size-10 place-items-center rounded-xl bg-[#e5f3e9] text-[17px] font-extrabold text-[#196b4a]" aria-hidden="true">{{ internship.title.slice(0, 1) || 'M' }}</span>
      <span class="rounded-[20px] bg-[#e8f4eb] px-2 py-[6px] text-[9px] font-bold text-[#408258]">Pendaftaran dibuka</span>
    </div>
    <p class="mb-[5px] mt-[19px] text-[11px] font-bold text-[#75817b]">Unit kampus</p>
    <h2 class="m-0 text-[17px] font-semibold tracking-[-0.5px]">{{ internship.title }}</h2>
    <p class="mb-[15px] mt-[9px] line-clamp-3 min-h-[57px] overflow-hidden text-[11px] leading-[1.7] text-[#75817b]">{{ internship.description }}</p>
    <p v-if="internship.cv_required" class="mb-3 text-[10px] font-semibold text-[#196b4a]">CV wajib dilampirkan</p>
    <div class="flex items-center justify-start gap-[15px] pb-4 text-[10px] text-[#526059]">
      <span>◷ {{ new Date(internship.start_date).toLocaleDateString('id-ID') }} – {{ new Date(internship.end_date).toLocaleDateString('id-ID') }}</span>
      <span>♙ {{ internship.quota }} posisi</span>
    </div>
    <div class="mt-auto flex min-h-[42px] items-center justify-end gap-2 border-t border-[#edf0ed] pt-3 text-[10px] text-[#89938c]">
      <button class="cursor-pointer border-0 bg-transparent p-0 text-xs font-bold text-[#196b4a]" type="button" @click="$emit('details', internship.id)">
        Lihat detail <span aria-hidden="true">→</span>
      </button>
    </div>
    <button
      v-if="isStudent"
      class="mt-4 flex min-h-[48px] w-full cursor-pointer items-center justify-center rounded-xl border border-[#145a3d] bg-[#145a3d] text-sm font-bold text-white transition hover:bg-[#0f4a32] disabled:cursor-default disabled:border-[#e4eee6] disabled:bg-[#e4eee6] disabled:text-[#408258]"
      type="button"
      :disabled="isApplied || applying"
      @click="$emit('apply', internship)"
    >{{ isApplied ? 'Sudah mengajukan' : applying ? 'Mengirim...' : internship.cv_required ? 'Lihat detail & unggah CV' : 'Ajukan sekarang' }}</button>
  </article>
</template>
