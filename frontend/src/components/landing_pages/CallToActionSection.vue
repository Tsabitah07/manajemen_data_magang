<template>
  <section id="mulai" class="cta-section">
    <div>
      <span class="section-label">WAKTUNYA AMBIL LANGKAH PERTAMA</span>
      <h2>Pengalaman pertamamu<br />dimulai hari ini.</h2>
    </div>
    <RouterLink class="button button-light" to="/lowongan">
      Yuk, mulai sekarang
      <svg viewBox="0 0 20 20" aria-hidden="true"><path d="M4 10h12m-5-5 5 5-5 5" /></svg>
    </RouterLink>
  </section>
</template>

<script setup lang="ts">
import { RouterLink } from 'vue-router'
</script>
