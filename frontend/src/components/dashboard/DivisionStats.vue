<script setup lang="ts">
defineProps<{
  activeInternships: number
  applicants: number
}>()
</script>

<template>
  <section class="mb-11 grid grid-cols-3 gap-[14px] max-[650px]:mb-[34px] max-[650px]:grid-cols-2 max-[440px]:grid-cols-1">
    <article class="flex min-h-[116px] items-center gap-[15px] rounded-xl border border-[#edf0ed] p-5">
      <span class="grid size-[42px] shrink-0 place-items-center rounded-xl bg-[#e5f3e9] text-[21px] text-[#196b4a]">▤</span>
      <div class="grid gap-[6px]"><small class="text-[11px] text-[#75817b]">Lowongan aktif</small><strong class="text-[26px]">{{ activeInternships }}</strong></div>
    </article>
    <article class="flex min-h-[116px] items-center gap-[15px] rounded-xl border border-[#edf0ed] p-5">
      <span class="grid size-[42px] shrink-0 place-items-center rounded-xl bg-[#fff0df] text-[21px] text-[#ce8735]">♙</span>
      <div class="grid gap-[6px]"><small class="text-[11px] text-[#75817b]">Total pendaftar</small><strong class="text-[26px]">{{ applicants }}</strong></div>
    </article>
    <article class="flex min-h-[116px] items-center gap-[15px] rounded-xl border border-[#edf0ed] p-5 max-[650px]:col-span-full max-[440px]:col-auto">
      <span class="grid size-[42px] shrink-0 place-items-center rounded-xl bg-[#efebfb] text-[21px] text-[#7961b3]">✦</span>
      <div class="grid gap-[6px]"><small class="text-[11px] text-[#75817b]">Langkah selanjutnya</small><strong class="text-sm">Tinjau kandidatmu</strong></div>
    </article>
  </section>
</template>
