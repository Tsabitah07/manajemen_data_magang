<script setup lang="ts">
import { RouterLink } from 'vue-router'

defineProps<{
  isRegister: boolean
  isSubmitting: boolean
  errorMessage: string
}>()

defineEmits<{
  submit: []
}>()

const name = defineModel<string>('name', { required: true })
const email = defineModel<string>('email', { required: true })
const password = defineModel<string>('password', { required: true })
</script>

<template>
  <section class="flex min-[851px]:min-h-[590px] flex-col justify-center rounded-[17px] border border-[#edf0ed] bg-white p-[34px] shadow-[0_20px_60px_#244c360e] max-[850px]:mx-auto max-[850px]:w-full max-[850px]:max-w-[520px] max-[520px]:px-5 max-[520px]:py-[25px]">
    <div class="auth-heading">
      <span class="text-[10px] font-extrabold tracking-[1.3px] text-[#196b4a]">{{ isRegister ? 'MULAI PERJALANANMU' : 'SENANG MELIHATMU LAGI' }}</span>
      <h2 class="my-[10px] mb-[7px] text-[27px] tracking-[-1px]">{{ isRegister ? 'Buat akun' : 'Masuk ke akunmu' }}</h2>
      <p class="text-xs leading-[1.6] text-[#75817b]">{{ isRegister ? 'Daftar gratis untuk mulai menggunakan RuangMagang.' : 'Lanjutkan perjalanan magangmu.' }}</p>
    </div>
    <form class="mt-[25px] grid gap-[15px]" @submit.prevent="$emit('submit')">
      <label v-if="isRegister" class="grid gap-[7px] text-xs font-bold text-[#3a4941]">
        <span>Nama lengkap</span>
        <input class="min-h-[46px] w-full rounded-lg border border-[#e3e9e4] bg-white px-[13px] text-inherit font-normal text-[#17251f] outline-none focus:border-[#196b4a] focus:ring-[3px] focus:ring-[#196b4a17]"         v-model.trim="name" autocomplete="name" required placeholder="Nama kamu" />
      </label>
      <label class="grid gap-[7px] text-xs font-bold text-[#3a4941]">
        <span>Email</span>
        <input
          class="min-h-[46px] w-full rounded-lg border border-[#e3e9e4] bg-white px-[13px] text-inherit font-normal text-[#17251f] outline-none focus:border-[#196b4a] focus:ring-[3px] focus:ring-[#196b4a17]"
          v-model.trim="email"
          autocomplete="email"
          type="email"
          required
          placeholder="nama@email.com"
        />
      </label>
      <label class="grid gap-[7px] text-xs font-bold text-[#3a4941]">
        <span>Kata sandi</span>
        <input
          class="min-h-[46px] w-full rounded-lg border border-[#e3e9e4] bg-white px-[13px] text-inherit font-normal text-[#17251f] outline-none focus:border-[#196b4a] focus:ring-[3px] focus:ring-[#196b4a17]"
          v-model="password"
          :autocomplete="isRegister ? 'new-password' : 'current-password'"
          type="password"
          minlength="8"
          required
          placeholder="Minimal 8 karakter"
        />
      </label>
      <p v-if="errorMessage" class="m-0 text-xs text-[#b54242]" role="alert">{{ errorMessage }}</p>
      <button class="flex min-h-[52px] w-full items-center gap-[11px] rounded-[9px] border border-[#196b4a] bg-[#196b4a] px-[21px] text-sm font-bold text-white transition hover:-translate-y-0.5 hover:bg-[#12583c] disabled:cursor-wait disabled:opacity-[0.65]" type="submit" :disabled="isSubmitting">
        {{ isSubmitting ? 'Mohon tunggu...' : isRegister ? 'Buat akun' : 'Masuk' }}
        <span class="ml-auto text-lg" aria-hidden="true">→</span>
      </button>
    </form>
    <p class="mt-5 text-center text-xs leading-[1.6] text-[#75817b]">
      {{ isRegister ? 'Sudah punya akun?' : 'Belum punya akun?' }}
      <RouterLink class="font-bold text-[#196b4a] no-underline" :to="isRegister ? '/login' : '/register'">
        {{ isRegister ? 'Masuk' : 'Daftar sekarang' }}
      </RouterLink>
    </p>
    <p class="mt-[23px] border-t border-[#edf0ed] pt-[17px] text-center text-xs leading-[1.6] text-[#75817b]">
      {{ isRegister ? 'Pendaftaran akun terbuka khusus untuk mahasiswa. Unit kampus mendapatkan akun dari administrator.' : 'Mahasiswa dan unit kampus dapat masuk menggunakan akun yang telah terdaftar.' }}
    </p>
  </section>
</template>
