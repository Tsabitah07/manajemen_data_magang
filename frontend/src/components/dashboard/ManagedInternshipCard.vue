<script setup lang="ts">
import type { Internship } from '@/services/api'

defineProps<{
  internship: Internship
}>()

defineEmits<{
  applicants: [internship: Internship]
  edit: [internship: Internship]
  delete: [internship: Internship]
}>()
</script>

<template>
  <article class="flex min-h-[92px] items-center gap-[13px] rounded-[11px] border border-[#edf0ed] p-[15px] max-[650px]:flex-wrap max-[650px]:items-start">
    <div class="grid size-[42px] shrink-0 place-items-center rounded-xl bg-[#e5f3e9] font-extrabold text-[#196b4a]">{{ internship.title.slice(0, 1) }}</div>
    <div class="min-w-0 flex-1 max-[650px]:w-[calc(100%-60px)] max-[650px]:flex-auto">
      <span class="text-[9px] font-bold text-[#89938c]">Unit kampus</span>
      <h3 class="my-1 overflow-hidden text-ellipsis whitespace-nowrap text-[13px] font-semibold">{{ internship.title }}</h3>
      <p class="m-0 text-[10px] text-[#89938c]">{{ internship.start_date }} – {{ internship.end_date }} · {{ internship.quota }} posisi</p>
    </div>
    <div class="flex items-center gap-[9px] max-[650px]:w-full max-[650px]:justify-end">
      <button class="cursor-pointer border-0 bg-transparent font-sans text-[10px] font-bold text-[#196b4a]" type="button" @click="$emit('applicants', internship)">Lihat riwayat</button>
    </div>
  </article>
</template>
