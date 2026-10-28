<script setup lang="ts">
import UserAvatar from '@/components/profile/UserAvatar.vue'

defineProps<{
  name: string
  isDivision: boolean
  profilePicture?: string | null
}>()
</script>

<template>
  <div class="flex items-center gap-[13px]">
    <UserAvatar :name="name" :profile-picture="profilePicture" />
    <div>
      <h2 class="mb-1 text-[15px] font-semibold">{{ name || 'Profil kamu' }}</h2>
      <span class="text-[10px] text-[#75817b]">{{ isDivision ? 'Unit kampus' : 'Mahasiswa' }}</span>
    </div>
  </div>
</template>
