<script setup lang="ts">
defineProps<{
  isDivision: boolean
}>()
</script>

<template>
  <aside class="sticky top-[30px] pt-5 max-[800px]:static max-[800px]:pt-0">
    <span class="section-label">IDENTITAS KAMPUS</span>
    <h1 class="my-[15px] mb-[13px] text-[clamp(38px,5vw,53px)] leading-[1.08] tracking-[-3px] max-[800px]:text-[41px]">Profil yang <span class="text-[#196b4a]">mewakilimu.</span></h1>
    <p class="text-[13px] leading-[1.8] text-[#75817b]">
      {{ isDivision
        ? 'Pastikan informasi akun unit kampus selalu akurat agar mahasiswa dapat mengenali lowongan yang kamu kelola.'
        : 'Informasi yang lengkap membantu unit kampus mengenalmu dan meninjau pengajuan magangmu.' }}
    </p>
    <div class="mt-[35px] flex gap-3 rounded-[11px] border border-[#e9eee9] bg-[#f7faf6] p-4 max-[800px]:hidden">
      <span class="text-xl text-[#196b4a]">✦</span>
      <p class="m-0 text-[10px] leading-[1.7] text-[#75817b]"><strong class="text-[11px] text-[#17251f]">Tips kecil</strong><br />Pastikan kontak dan tautan portofoliomu aktif agar mudah dihubungi.</p>
    </div>
  </aside>
</template>
