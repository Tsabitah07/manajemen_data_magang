<script setup lang="ts">
import { ref } from 'vue'
import { RouterLink, useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const auth = useAuthStore()
const router = useRouter()
const menuOpen = ref(false)

async function logout() {
  try {
    await auth.logout()
    await router.push('/')
  } catch (error) {
    window.alert(error instanceof Error ? error.message : 'Keluar gagal.')
  }
}
</script>

<template>
  <div class="flex min-h-screen flex-col text-[#17251f]">
    <header class="mx-auto flex min-h-[82px] w-[min(1160px,calc(100%-64px))] items-center justify-between border-b border-[#edf0ed] max-[680px]:min-h-[72px] max-[680px]:w-[calc(100%-40px)]">
      <RouterLink class="inline-flex items-center gap-[9px] text-xl font-extrabold tracking-[-1px] text-[#17251f] no-underline" to="/" aria-label="RuangMagang, beranda">
        <span class="grid size-[35px] place-items-center rounded-[11px] bg-[#196b4a]" aria-hidden="true">
          <svg class="w-[23px] stroke-white [stroke-linecap:round] [stroke-linejoin:round] [stroke-width:1.6]" viewBox="0 0 24 24" fill="none">
            <path d="M4 8.5 12 4l8 4.5v7L12 20l-8-4.5v-7Z" />
            <path d="m8 10.5 4 2.2 4-2.2M12 13v4" />
          </svg>
        </span>
        <span>ruang<span class="text-[#196b4a]">magang</span></span>
      </RouterLink>
      <button
        class="hidden h-[39px] w-[39px] flex-col items-center justify-center gap-[5px] rounded-[9px] border border-[#e7ece8] bg-white max-[680px]:flex"
        type="button"
        :aria-expanded="menuOpen"
        aria-label="Buka menu navigasi"
        @click="menuOpen = !menuOpen"
      >
        <span class="h-0.5 w-4 rounded-sm bg-[#17251f]"></span><span class="h-0.5 w-4 rounded-sm bg-[#17251f]"></span><span class="h-0.5 w-4 rounded-sm bg-[#17251f]"></span>
      </button>
      <nav class="flex items-center gap-[25px] max-[680px]:absolute max-[680px]:left-5 max-[680px]:right-5 max-[680px]:top-[65px] max-[680px]:z-[5] max-[680px]:hidden max-[680px]:flex-col max-[680px]:items-stretch max-[680px]:gap-0 max-[680px]:rounded-xl max-[680px]:border max-[680px]:border-[#edf0ed] max-[680px]:bg-white max-[680px]:p-[10px] max-[680px]:shadow-[0_16px_35px_#193c2817]" :class="menuOpen ? 'max-[680px]:flex' : ''">
        <RouterLink class="text-[13px] font-semibold text-[#526059] no-underline hover:text-[#196b4a] router-link-active:text-[#196b4a] max-[680px]:p-[13px_10px]" to="/lowongan" @click="menuOpen = false">Jelajahi magang</RouterLink>
        <RouterLink v-if="auth.user && auth.user.role !== 'admin'" class="text-[13px] font-semibold text-[#526059] no-underline hover:text-[#196b4a] router-link-active:text-[#196b4a] max-[680px]:p-[13px_10px]" to="/dashboard" @click="menuOpen = false">Dashboard</RouterLink>
        <RouterLink v-if="auth.user && auth.user.role !== 'admin'" class="text-[13px] font-semibold text-[#526059] no-underline hover:text-[#196b4a] router-link-active:text-[#196b4a] max-[680px]:p-[13px_10px]" to="/profil" @click="menuOpen = false">Profil</RouterLink>
        <template v-if="auth.user">
          <span class="text-xs text-[#75817b] max-[680px]:p-[13px_10px]">{{ auth.user.name }}</span>
          <button class="mt-[6px] inline-flex min-h-[42px] items-center justify-center rounded-lg border border-[#196b4a] bg-white px-[17px] text-xs font-bold text-[#196b4a] max-[680px]:mt-[6px]" type="button" @click="logout">
            Keluar
          </button>
        </template>
        <RouterLink v-else class="mt-[6px] inline-flex min-h-[42px] items-center justify-center rounded-lg border border-[#196b4a] bg-[#196b4a] px-[17px] text-xs font-bold text-white no-underline hover:bg-[#12583c] max-[680px]:mt-[6px]" to="/login" @click="menuOpen = false">
          Masuk
        </RouterLink>
      </nav>
    </header>
    <main class="mx-auto w-[min(1160px,calc(100%-64px))] flex-1 py-[45px] pb-[75px] max-[680px]:w-[calc(100%-40px)]"><slot /></main>
    <footer class="mx-auto flex w-[min(1160px,calc(100%-64px))] justify-between gap-[15px] border-t border-[#edf0ed] py-6 text-[11px] text-[#839087] max-[680px]:w-[calc(100%-40px)] max-[680px]:flex-col">
      <span>© 2026 RuangMagang</span>
      <span>Tempat langkah pertama menuju karier impian.</span>
    </footer>
  </div>
</template>
