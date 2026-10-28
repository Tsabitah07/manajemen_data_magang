<script setup lang="ts">
import { computed, onBeforeUnmount, onMounted, ref } from 'vue'
import AppLayout from '@/components/AppLayout.vue'
import ProfileIntro from '@/components/profile/ProfileIntro.vue'
import ProfileCardHeading from '@/components/profile/ProfileCardHeading.vue'
import UserAvatar from '@/components/profile/UserAvatar.vue'
import { apiRequest, unwrapData, type ApiEnvelope, type User } from '@/services/api'
import { useAuthStore } from '@/stores/auth'

const auth = useAuthStore()
const isCampusUnit = computed(() => auth.user?.role === 'campus-unit')
const name = ref(auth.user?.name ?? '')
const phone = ref(auth.user?.phone ?? '')
const nim = ref('')
const major = ref('')
const gender = ref<'male' | 'female' | ''>('')
const enrollmentYear = ref<number | ''>('')
const unitName = ref('')
const position = ref('')
const loading = ref(true)
const saving = ref(false)
const errorMessage = ref('')
const notice = ref('')
const selectedPicture = ref<File | null>(null)
const picturePreview = ref('')

function clearPicturePreview() {
  if (picturePreview.value) URL.revokeObjectURL(picturePreview.value)
  picturePreview.value = ''
}

function selectProfilePicture(event: Event) {
  const input = event.target as HTMLInputElement
  const file = input.files?.[0]
  if (!file) return

  clearPicturePreview()
  selectedPicture.value = null
  errorMessage.value = ''

  if (!file.type.startsWith('image/')) {
    errorMessage.value = 'Pilih file gambar untuk foto profil.'
    input.value = ''
    return
  }
  if (file.size > 2 * 1024 * 1024) {
    errorMessage.value = 'Ukuran foto profil maksimal 2 MB.'
    input.value = ''
    return
  }

  selectedPicture.value = file
  picturePreview.value = URL.createObjectURL(file)
}

async function loadProfile() {
  loading.value = true
  errorMessage.value = ''
  try {
    const profile = await apiRequest<User>('/auth/profile', {}, auth.token)
    auth.updateUser(profile)
    name.value = profile.name
    phone.value = profile.phone ?? ''
    nim.value = profile.student_profile?.nim ?? ''
    major.value = profile.student_profile?.major ?? ''
    gender.value = profile.student_profile?.gender ?? ''
    enrollmentYear.value = profile.student_profile?.enrollment_year ?? ''
    unitName.value = profile.campus_unit_profile?.unit_name ?? ''
    position.value = profile.campus_unit_profile?.position ?? ''

  } catch (error) {
    errorMessage.value =
      error instanceof Error ? error.message : 'Profil belum dapat dimuat. Coba lagi.'
  } finally {
    loading.value = false
  }
}

async function saveProfile() {
  if (!auth.user) return
  saving.value = true
  errorMessage.value = ''
  notice.value = ''
  try {
    const profileData: Record<string, string | number | null> = {
      name: name.value.trim(),
      phone: phone.value.trim() || null,
      ...(isCampusUnit.value
        ? { unit_name: unitName.value, position: position.value }
        : {
            nim: nim.value.trim(),
            major: major.value.trim(),
            gender: gender.value || null,
            enrollment_year: enrollmentYear.value || null,
          }),
    }
    const pictureFile = selectedPicture.value
    let method: 'POST' | 'PUT' = 'PUT'
    let body: BodyInit = JSON.stringify(profileData)
    if (pictureFile) {
      const formData = new FormData()
      formData.set('_method', 'PUT')
      Object.entries(profileData).forEach(([key, value]) => {
        formData.set(key, value === null ? '' : String(value))
      })
      formData.set('profile_picture', pictureFile)
      method = 'POST'
      body = formData
    }
    const response = await apiRequest<ApiEnvelope<User>>(
      '/auth/update',
      {
        method,
        body,
      },
      auth.token,
    )
    const updatedUser = unwrapData<User>(response)
    auth.updateUser(updatedUser)
    name.value = updatedUser.name
    phone.value = updatedUser.phone ?? ''
    nim.value = updatedUser.student_profile?.nim ?? ''
    major.value = updatedUser.student_profile?.major ?? ''
    gender.value = updatedUser.student_profile?.gender ?? ''
    enrollmentYear.value = updatedUser.student_profile?.enrollment_year ?? ''
    unitName.value = updatedUser.campus_unit_profile?.unit_name ?? ''
    position.value = updatedUser.campus_unit_profile?.position ?? ''
    selectedPicture.value = null
    clearPicturePreview()
    notice.value = 'Profil berhasil diperbarui.'
  } catch (error) {
    errorMessage.value =
      error instanceof Error ? error.message : 'Perubahan profil belum dapat disimpan.'
  } finally {
    saving.value = false
  }
}

onBeforeUnmount(clearPicturePreview)
onMounted(loadProfile)
</script>

<template>
  <AppLayout>
    <section class="grid grid-cols-[0.8fr_1.1fr] items-start gap-[75px] pt-[18px] max-[800px]:grid-cols-1 max-[800px]:gap-7">
      <ProfileIntro :is-division="isCampusUnit" />

      <div>
        <section class="overflow-hidden rounded-[15px] border border-[#edf0ed] bg-white shadow-[0_18px_50px_#244c360b]">
          <div class="flex items-center gap-[13px] border-b border-[#edf0ed] bg-[#fbfcfa] px-[25px] py-[23px] max-[480px]:px-[18px]">
            <ProfileCardHeading
              :name="name"
              :is-division="isCampusUnit"
              :profile-picture="auth.user?.profile_picture"
            />
          </div>
          <div v-if="loading" class="p-10 text-center text-xs text-[#75817b]">Memuat informasi profil...</div>
          <form v-else class="grid gap-[17px] p-[25px] max-[480px]:px-[18px]" @submit.prevent="saveProfile">
            <h2 class="m-0 text-sm font-semibold">Informasi akun</h2>
            <div class="grid gap-[10px]">
              <span class="text-[11px] font-bold text-[#3a4941]">Foto profil</span>
              <div class="flex items-center gap-4">
                <UserAvatar
                  :name="name"
                  :profile-picture="picturePreview || auth.user?.profile_picture"
                  size="large"
                />
                <label class="grid gap-1.5 text-[11px] font-semibold text-[#196b4a]">
                  <span>{{ selectedPicture ? 'Ganti foto' : 'Pilih foto baru' }}</span>
                  <input
                    class="max-w-full text-[11px] font-normal text-[#75817b] file:mr-2 file:rounded-md file:border file:border-[#dce6dd] file:bg-white file:px-3 file:py-2 file:font-semibold file:text-[#196b4a]"
                    type="file"
                    accept="image/*"
                    aria-label="Pilih foto profil"
                    @change="selectProfilePicture"
                  />
                </label>
              </div>
              <p class="m-0 text-[10px] text-[#75817b]">Format gambar, maksimal 2 MB.</p>
            </div>
            <label class="grid gap-[7px] text-[11px] font-bold text-[#3a4941]">
              <span>Nama lengkap *</span>
              <input
                v-model.trim="name"
                class="min-h-[43px] w-full rounded-lg border border-[#e3e9e4] px-3 py-[10px] text-inherit font-normal text-[#17251f] outline-none focus:border-[#196b4a] focus:ring-[3px] focus:ring-[#196b4a17]"
                autocomplete="name"
                required
              />
            </label>
            <label class="grid gap-[7px] text-[11px] font-bold text-[#3a4941]">
              <span>Email akun</span>
              <input
                class="min-h-[43px] w-full rounded-lg border border-[#e3e9e4] bg-[#f5f7f5] px-3 py-[10px] text-inherit font-normal text-[#89938c]"
                :value="auth.user?.email"
                type="email"
                disabled
              />
            </label>
            <label class="grid gap-[7px] text-[11px] font-bold text-[#3a4941]">
              <span>Nomor telepon</span>
              <input
                v-model.trim="phone"
                class="min-h-[43px] w-full rounded-lg border border-[#e3e9e4] px-3 py-[10px] text-inherit font-normal text-[#17251f] outline-none focus:border-[#196b4a] focus:ring-[3px] focus:ring-[#196b4a17]"
                type="tel"
                autocomplete="tel"
              />
            </label>
            <template v-if="isCampusUnit">
              <h2 class="mb-0 mt-2 text-sm font-semibold">Informasi unit kampus</h2>
              <label class="grid gap-[7px] text-[11px] font-bold text-[#3a4941]">
                <span>Nama unit *</span>
                <select
                  v-model="unitName"
                  class="min-h-[43px] w-full rounded-lg border border-[#e3e9e4] bg-white px-3 py-[10px] text-inherit font-normal text-[#17251f] outline-none focus:border-[#196b4a] focus:ring-[3px] focus:ring-[#196b4a17]"
                  required
                >
                  <option disabled value="">Pilih unit</option>
                  <option>Lecturer</option>
                  <option>Admissions and Enrollment</option>
                  <option>Academic Affairs</option>
                  <option>Student Affairs</option>
                  <option>Research and Development</option>
                  <option>Finance and Administration</option>
                  <option>Human Resources</option>
                  <option>Information Technology</option>
                  <option>Library Services</option>
                  <option>International Relations</option>
                  <option>Alumni Relations</option>
                </select>
              </label>
              <label class="grid gap-[7px] text-[11px] font-bold text-[#3a4941]">
                <span>Jabatan *</span>
                <select
                  v-model="position"
                  class="min-h-[43px] w-full rounded-lg border border-[#e3e9e4] bg-white px-3 py-[10px] text-inherit font-normal text-[#17251f] outline-none focus:border-[#196b4a] focus:ring-[3px] focus:ring-[#196b4a17]"
                  required
                >
                  <option disabled value="">Pilih jabatan</option>
                  <option>Head of Unit</option>
                  <option>Deputy Head of Unit</option>
                  <option>Coordinator</option>
                  <option>Staff</option>
                </select>
              </label>
            </template>
            <template v-else>
              <h2 class="mb-0 mt-2 text-sm font-semibold">Informasi mahasiswa</h2>
              <label class="grid gap-[7px] text-[11px] font-bold text-[#3a4941]">
                <span>NIM *</span>
                <input
                  v-model.trim="nim"
                  class="min-h-[43px] w-full rounded-lg border border-[#e3e9e4] px-3 py-[10px] text-inherit font-normal text-[#17251f] outline-none focus:border-[#196b4a] focus:ring-[3px] focus:ring-[#196b4a17]"
                  required
                />
              </label>
              <label class="grid gap-[7px] text-[11px] font-bold text-[#3a4941]">
                <span>Program studi *</span>
                <input
                  v-model.trim="major"
                  class="min-h-[43px] w-full rounded-lg border border-[#e3e9e4] px-3 py-[10px] text-inherit font-normal text-[#17251f] outline-none focus:border-[#196b4a] focus:ring-[3px] focus:ring-[#196b4a17]"
                  required
                />
              </label>
              <div class="grid grid-cols-2 gap-3 max-[480px]:grid-cols-1">
                <label class="grid gap-[7px] text-[11px] font-bold text-[#3a4941]">
                  <span>Jenis kelamin</span>
                  <select
                    v-model="gender"
                    class="min-h-[43px] w-full rounded-lg border border-[#e3e9e4] bg-white px-3 py-[10px] text-inherit font-normal text-[#17251f] outline-none focus:border-[#196b4a]"
                  >
                    <option value="">Pilih jenis kelamin</option>
                    <option value="female">Perempuan</option>
                    <option value="male">Laki-laki</option>
                  </select>
                </label>
                <label class="grid gap-[7px] text-[11px] font-bold text-[#3a4941]">
                  <span>Tahun masuk</span>
                  <input
                    v-model.number="enrollmentYear"
                    class="min-h-[43px] w-full rounded-lg border border-[#e3e9e4] px-3 py-[10px] text-inherit font-normal text-[#17251f] outline-none focus:border-[#196b4a]"
                    type="number"
                    min="1900"
                    :max="new Date().getFullYear()"
                  />
                </label>
              </div>
              <p v-if="auth.user?.student_profile?.status" class="m-0 text-[11px] text-[#75817b]">
                Status mahasiswa: <strong class="text-[#17251f]">{{ auth.user.student_profile.status }}</strong>
              </p>
            </template>
            <p v-if="errorMessage" class="m-0 text-[11px] text-[#b54242]" role="alert">{{ errorMessage }}</p>
            <p v-if="notice" class="m-0 text-[11px] text-[#28744e]" role="status">{{ notice }}</p>
            <div class="mt-[7px] flex items-center justify-between gap-2.5 border-t border-[#edf0ed] pt-[18px] max-[480px]:flex-col-reverse max-[480px]:items-stretch">
              <RouterLink class="text-[11px] font-semibold text-[#75817b] no-underline max-[480px]:text-center" to="/dashboard">Kembali ke dashboard</RouterLink>
              <button
                class="inline-flex min-h-[46px] items-center justify-center rounded-[9px] border border-[#196b4a] bg-[#196b4a] px-[21px] text-xs font-bold text-white disabled:cursor-wait disabled:opacity-[0.65]"
                type="submit"
                :disabled="saving"
              >{{ saving ? 'Menyimpan...' : 'Simpan perubahan' }}</button>
            </div>
          </form>
        </section>

      </div>
    </section>
  </AppLayout>
</template>
