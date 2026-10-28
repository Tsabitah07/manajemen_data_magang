<script setup lang="ts">
defineProps<{
  applications: number
  accepted: number
}>()
</script>

<template>
  <section class="mb-[39px] grid grid-cols-[1fr_1fr_1.3fr] gap-[13px] max-[650px]:grid-cols-2 max-[440px]:grid-cols-1">
    <article class="flex min-h-[135px] flex-col items-start justify-center rounded-xl border border-[#edf0ed] p-5 text-[#17251f] no-underline">
      <span class="text-[9px] font-extrabold tracking-[1.3px] text-[#196b4a]">TOTAL PENGAJUAN</span>
      <strong class="my-[3px] text-[31px]">{{ applications }}</strong>
      <span class="text-[10px] text-[#75817b]">langkah menuju pengalaman baru</span>
    </article>
    <article class="flex min-h-[135px] flex-col items-start justify-center rounded-xl border border-[#edf0ed] bg-[#f5f9f4] p-5 text-[#17251f] no-underline">
      <span class="text-[9px] font-extrabold tracking-[1.3px] text-[#196b4a]">SUDAH DITERIMA</span>
      <strong class="my-[3px] text-[31px]">{{ accepted }}</strong>
      <span class="text-[10px] text-[#75817b]">kesempatan untuk bertumbuh</span>
    </article>
    <RouterLink class="relative flex min-h-[135px] flex-row items-center justify-start gap-[13px] rounded-xl border border-[#edf0ed] bg-[#1b6849] p-5 text-white no-underline max-[650px]:col-span-full max-[440px]:col-auto" to="/lowongan">
      <span class="grid size-[42px] shrink-0 place-items-center rounded-xl bg-white/15 text-[21px] text-white">↗</span><span><strong class="block text-[13px]">Masih ada banyak peluang</strong><small class="mt-[6px] block text-[10px] text-[#c8e5d1]">Jelajahi lowongan terbaru</small></span><span class="ml-auto text-xl">→</span>
    </RouterLink>
  </section>
</template>
