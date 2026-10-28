<script setup lang="ts">
import { computed, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import AuthCard from '@/components/auth/AuthCard.vue'
import AuthIntro from '@/components/auth/AuthIntro.vue'

const route = useRoute()
const router = useRouter()
const auth = useAuthStore()
const isRegister = computed(() => route.path === '/register')
const name = ref('')
const email = ref('')
const password = ref('')
const errorMessage = ref('')
const isSubmitting = ref(false)

async function submit() {
  errorMessage.value = ''
  isSubmitting.value = true
  try {
    if (isRegister.value) {
      await auth.register({
        name: name.value,
        email: email.value,
        password: password.value,
      })
    } else {
      await auth.login(email.value, password.value)
    }
    const redirect =
      typeof route.query.redirect === 'string'
        ? route.query.redirect
        : auth.user?.role === 'student' || auth.user?.role === 'campus-unit'
          ? '/dashboard'
          : '/'
    await router.push(redirect)
  } catch (error) {
    errorMessage.value = error instanceof Error ? error.message : 'Terjadi kesalahan. Coba lagi.'
  } finally {
    isSubmitting.value = false
  }
}
</script>

<template>
  <div class="grid min-h-[100vh] grid-cols-[1fr_0.8fr] items-center gap-[100px] max-[850px]:grid-cols-1 max-[850px]:gap-6 max-[850px]:px-5 max-[850px]:py-6">
    <AuthIntro />
    <AuthCard
      v-model:name="name"
      v-model:email="email"
      v-model:password="password"
      :is-register="isRegister"
      :is-submitting="isSubmitting"
      :error-message="errorMessage"
      @submit="submit"
    />
  </div>
</template>
