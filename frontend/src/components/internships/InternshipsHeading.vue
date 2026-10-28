<script setup lang="ts">
const search = defineModel<string>({ required: true })
</script>

<template>
  <section class="flex items-end justify-between gap-[30px] py-[18px] pb-[35px] max-[620px]:items-stretch max-[620px]:flex-col max-[620px]:gap-5 max-[620px]:pt-[5px]">
    <div>
      <span class="text-[10px] font-extrabold tracking-[1.3px] text-[#196b4a]">PELUANG DI LINGKUNGAN KAMPUS</span>
      <h1 class="my-[13px] mb-[10px] text-[clamp(35px,5vw,52px)] leading-[1.1] tracking-[-2.8px] max-[620px]:text-[39px] max-[620px]:tracking-[-2px]">Temukan tempatmu <span class="text-[#196b4a]">bertumbuh.</span></h1>
      <p class="m-0 max-w-[550px] text-sm leading-[1.7] text-[#75817b]">Jelajahi lowongan magang dari unit kampus dan pilih pengalaman yang paling cocok.</p>
    </div>
    <label class="flex min-h-[47px] w-[min(340px,100%)] items-center gap-2.5 rounded-[9px] border border-[#e3e9e4] px-[13px] text-[#196b4a] max-[620px]:w-full">
      <span class="text-[22px]" aria-hidden="true">⌕</span>
      <input class="w-full border-0 text-xs text-[#17251f] outline-none" v-model="search" type="search" placeholder="Cari posisi atau unit kampus..." />
    </label>
  </section>
</template>
