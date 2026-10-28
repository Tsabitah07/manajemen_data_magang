<script setup lang="ts">
import { computed, ref, watch } from 'vue'
import { backendAssetUrl } from '@/services/api'

const props = withDefaults(
  defineProps<{
    name: string
    profilePicture?: string | null
    size?: 'small' | 'large'
  }>(),
  {
    profilePicture: null,
    size: 'small',
  },
)

const imageFailed = ref(false)
const initials = computed(() => {
  const words = props.name.trim().split(/\s+/).filter(Boolean)
  const firstWord = words[0]
  if (!firstWord) return '?'
  const lastWord = words.at(-1) ?? firstWord
  return words.length === 1
    ? firstWord.slice(0, 2).toUpperCase()
    : `${firstWord[0]}${lastWord[0]}`.toUpperCase()
})
const backgroundColor = computed(() => {
  let hash = 0
  for (const character of props.name) {
    hash = (hash * 31 + character.charCodeAt(0)) >>> 0
  }
  const colors = ['#dcefe4', '#e3e8fa', '#f8e6d9', '#f3e2ef', '#e2edf2', '#f4edcf']
  return colors[hash % colors.length]
})
const imageUrl = computed(() =>
  props.profilePicture ? backendAssetUrl(props.profilePicture) : '',
)
const sizeClass = computed(() => (props.size === 'large' ? 'size-16 text-xl' : 'size-11 text-sm'))

watch(imageUrl, () => {
  imageFailed.value = false
})
</script>

<template>
  <img
    v-if="imageUrl && !imageFailed"
    :src="imageUrl"
    :alt="`Foto profil ${name}`"
    class="shrink-0 rounded-full object-cover"
    :class="sizeClass"
    @error="imageFailed = true"
  />
  <span
    v-else
    class="grid shrink-0 place-items-center rounded-full font-bold text-[#315b47]"
    :class="sizeClass"
    :style="{ backgroundColor }"
    role="img"
    :aria-label="`Foto profil ${name}, inisial ${initials}`"
  >
    {{ initials }}
  </span>
</template>
