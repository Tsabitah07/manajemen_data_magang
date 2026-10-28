<script setup lang="ts">
defineProps<{
  isDivision: boolean
  name: string
}>()
</script>

<template>
  <section class="flex items-center justify-between gap-5 py-3 pb-[30px] max-[650px]:items-start max-[650px]:flex-col max-[650px]:gap-[15px]">
    <div>
      <span class="section-label">{{ isDivision ? 'RUANG KERJAMU' : 'PERJALANAN MAGANGMU' }}</span>
      <h1 class="my-[13px] mb-[7px] text-[38px] tracking-[-2px] max-[650px]:text-[33px]">Halo, {{ name.split(' ')[0] }} <span class="text-[25px]">👋</span></h1>
      <p class="m-0 text-xs text-[#75817b]">
        {{
          isDivision
            ? 'Kelola lowongan dan temukan mahasiswa yang tepat untuk timmu.'
            : 'Semua pengajuan dan kabar terbaru ada di satu tempat.'
        }}
      </p>
    </div>
    <RouterLink class="text-xs font-bold text-[#196b4a] no-underline" to="/profil">Lengkapi profil <span class="ml-[7px]">→</span></RouterLink>
  </section>
</template>
