# Product Requirements Document (PRD)
## Sistem Manajemen Magang Berbasis Kampus

| | |
|---|---|
| **Penulis** | Inas Tsabitah Dien (2510101005) |
| **Mata Kuliah** | Rekayasa Perangkat Lunak - Semester 3 |
| **Versi** | 1.0 |
| **Tanggal** | 17 September 2026 |
| **Status** | Draft |

---

## 1. Latar Belakang & Masalah

Banyak divisi/organisasi di kampus membutuhkan asisten mahasiswa untuk membantu pekerjaan administratif, teknis, atau proyek tertentu, tetapi belum memiliki platform terpusat untuk merekrut dan mengelola magang. Di sisi lain, mahasiswa kesulitan menemukan peluang magang yang relevan karena informasi tersebar di berbagai grup WhatsApp, poster fisik, atau dari mulut ke mulut.

Proses rekrutmen manual yang berjalan saat ini:
- Tidak terdokumentasi dengan baik
- Menyulitkan tracking pendaftar
- Membuat mahasiswa bingung harus mendaftar ke mana

**Pernyataan Masalah:** Kampus membutuhkan satu platform terpusat yang menghubungkan divisi/organisasi kampus (pemberi magang) dengan mahasiswa (pencari magang), lengkap dengan alur pendaftaran dan seleksi yang transparan.

---

## 2. Tujuan Produk

1. Menyediakan satu platform terpusat untuk publikasi lowongan magang internal kampus.
2. Menyederhanakan proses pendaftaran dan seleksi mahasiswa magang.
3. Memberikan visibilitas status pendaftaran secara real-time kepada mahasiswa.
4. Mendokumentasikan riwayat magang mahasiswa untuk kebutuhan portofolio/evaluasi.

---

## 3. Target Pengguna & Persona

### 3.1 Divisi/Organisasi Kampus
Admin divisi, ketua organisasi, atau staf yang bertanggung jawab merekrut dan mengelola mahasiswa magang. Membutuhkan cara efisien untuk mempublikasikan kebutuhan dan menyeleksi pendaftar.

### 3.2 Mahasiswa
Mahasiswa aktif yang mencari pengalaman magang, membangun portofolio, atau memenuhi kebutuhan akademik/kurikuler. Membutuhkan akses mudah ke peluang magang yang terpercaya.

---

## 4. Manfaat Produk

- Memudahkan divisi kampus mempublikasikan kebutuhan magang secara terstruktur dan standar
- Mahasiswa dapat menemukan dan mendaftar magang dengan mudah dalam satu platform terpusat
- Proses seleksi dan tracking magang terdokumentasi dengan baik dan transparan
- Meningkatkan keterhubungan antara divisi kampus dan mahasiswa
- Data magang tersimpan rapi untuk kebutuhan laporan, evaluasi, dan portofolio

---

## 5. Ruang Lingkup

### 5.1 Dalam Cakupan (In Scope) — Fitur Inti

| # | Fitur | Deskripsi Singkat |
|---|---|---|
| 1 | Autentikasi 2 role | Login/register terpisah untuk role Divisi dan Mahasiswa |
| 2 | CRUD Lowongan Magang | Divisi dapat membuat, mengedit, menghapus postingan (judul, deskripsi, kuantitas, durasi, requirement) |
| 3 | Listing & Pendaftaran | Mahasiswa melihat daftar magang tersedia dan mendaftar |
| 4 | Dashboard per role | Tampilan berbeda untuk Divisi (pendaftar) dan Mahasiswa (status) |
| 5 | Status Pendaftaran | Pending, Diterima, Ditolak |
| 6 | Profil Mahasiswa | Data dasar + portofolio |
| 7 | Notifikasi sederhana | Update status pendaftaran |

### 5.2 Di Luar Cakupan (Out of Scope)

- Sistem pembayaran/gaji magang
- Video interview terintegrasi
- Integrasi dengan sistem akademik kampus (SIAKAD)
- Chat real-time antar pengguna
- Sistem rating/review setelah magang selesai
- Export data ke PDF/Excel untuk laporan resmi
- Presensi/kehadiran magang

---

## 6. Tech Stack

| Layer | Teknologi | Keterangan |
|---|---|---|
| **Backend** | Go + Gin-Gonic | REST API, routing, middleware autentikasi |
| **Frontend** | Vue.js (3.x, Composition API disarankan) | SPA, konsumsi REST API |
| **Database** | PostgreSQL / MySQL | Relasional, sesuai kebutuhan data persisten |
| **ORM** | GORM | Interaksi Go ↔ database |
| **Autentikasi** | JWT (JSON Web Token) | Session stateless, role-based access |
| **HTTP Client (FE)** | Axios | Komunikasi FE ↔ BE |
| **State Management (FE)** | Pinia | Manajemen state role & data user |
| **Routing (FE)** | Vue Router | Termasuk route guard per role |
| **Styling (FE)** | Tailwind CSS (opsional) | Mempercepat pengembangan UI |
| **Deployment (opsional)** | Docker | Containerisasi BE & FE |

**Arsitektur Umum:** Vue.js (SPA) ⇄ REST API (Gin-Gonic, JSON) ⇄ PostgreSQL/MySQL

---

## 7. Rincian Fitur & Functional Requirements

### 7.1 Autentikasi & Role
- FR-1.1: Sistem dapat membedakan 2 role: `divisi` dan `mahasiswa`
- FR-1.2: Registrasi akun dengan email, password, dan role
- FR-1.3: Login menghasilkan JWT yang menyimpan `user_id` dan `role`
- FR-1.4: Middleware Gin memvalidasi token & membatasi akses endpoint sesuai role

### 7.2 Manajemen Lowongan Magang (Role: Divisi)
- FR-2.1: Divisi dapat membuat lowongan baru (judul, deskripsi, kuantitas, durasi, requirement, deadline)
- FR-2.2: Divisi dapat mengedit lowongan miliknya
- FR-2.3: Divisi dapat menghapus lowongan miliknya
- FR-2.4: Divisi hanya dapat mengelola lowongan yang dibuat oleh akunnya sendiri

### 7.3 Pencarian & Pendaftaran Magang (Role: Mahasiswa)
- FR-3.1: Mahasiswa dapat melihat daftar seluruh lowongan yang masih aktif
- FR-3.2: Mahasiswa dapat melihat detail satu lowongan
- FR-3.3: Mahasiswa dapat mendaftar ke satu lowongan (tidak bisa daftar dua kali ke lowongan yang sama)
- FR-3.4: Mahasiswa dapat membatalkan pendaftaran selama status masih pending

### 7.4 Seleksi & Status Pendaftaran
- FR-4.1: Divisi dapat melihat daftar pendaftar per lowongan
- FR-4.2: Divisi dapat mengubah status pendaftar: pending → diterima / ditolak
- FR-4.3: Mahasiswa dapat melihat status pendaftarannya secara real-time saat membuka dashboard

### 7.5 Dashboard
- FR-5.1: Dashboard Divisi menampilkan ringkasan lowongan aktif dan jumlah pendaftar per lowongan
- FR-5.2: Dashboard Mahasiswa menampilkan riwayat pendaftaran beserta statusnya

### 7.6 Profil Mahasiswa
- FR-6.1: Mahasiswa dapat mengisi/mengedit data dasar (nama, NIM, jurusan, kontak)
- FR-6.2: Mahasiswa dapat menambahkan tautan/deskripsi portofolio

### 7.7 Notifikasi
- FR-7.1: Sistem menampilkan notifikasi in-app sederhana saat status pendaftaran berubah

---

## 8. Non-Functional Requirements

| Kategori | Requirement |
|---|---|
| **Usability** | Aplikasi berbasis web dan responsif (mobile & desktop) |
| **Persistensi Data** | Data tersimpan di database, tidak hilang saat refresh |
| **Keamanan** | Password di-hash (bcrypt), endpoint dilindungi JWT & role-based middleware |
| **Performa** | Response API rata-rata < 1 detik untuk operasi standar (CRUD) |
| **Maintainability** | Struktur kode backend mengikuti pola layer (handler-service-repository) |
| **Ketersediaan** | Aplikasi dapat diakses melalui browser modern (Chrome, Firefox, Edge) |

---

## 9. Alur Pengguna Utama (User Flow)

**Alur Divisi:**
Register/Login → Dashboard Divisi → Buat Lowongan → Lihat Daftar Pendaftar → Ubah Status Pendaftar → Notifikasi terkirim ke Mahasiswa

**Alur Mahasiswa:**
Register/Login → Lengkapi Profil → Lihat Daftar Lowongan → Daftar ke Lowongan → Pantau Status di Dashboard → Terima Notifikasi Update Status

---

## 10. Model Data (Ringkas)

**users**
`id, nama, email, password_hash, role (divisi/mahasiswa), created_at`

**mahasiswa_profil**
`id, user_id (FK), nim, jurusan, kontak, portofolio, created_at`

**divisi_profil**
`id, user_id (FK), nama_divisi, deskripsi_divisi, created_at`

**lowongan**
`id, divisi_id (FK), judul, deskripsi, kuantitas, durasi, requirement, deadline, status_aktif, created_at`

**pendaftaran**
`id, lowongan_id (FK), mahasiswa_id (FK), status (pending/diterima/ditolak), created_at, updated_at`

**notifikasi**
`id, user_id (FK), pesan, is_read, created_at`

---

## 11. Contoh Endpoint API (Gin-Gonic)

| Method | Endpoint | Role | Deskripsi |
|---|---|---|---|
| POST | `/api/auth/register` | Public | Registrasi akun |
| POST | `/api/auth/login` | Public | Login, mengembalikan JWT |
| GET | `/api/lowongan` | Public/Mahasiswa | List lowongan aktif |
| GET | `/api/lowongan/:id` | Public/Mahasiswa | Detail lowongan |
| POST | `/api/lowongan` | Divisi | Buat lowongan baru |
| PUT | `/api/lowongan/:id` | Divisi | Edit lowongan |
| DELETE | `/api/lowongan/:id` | Divisi | Hapus lowongan |
| POST | `/api/lowongan/:id/daftar` | Mahasiswa | Daftar ke lowongan |
| GET | `/api/lowongan/:id/pendaftar` | Divisi | Lihat daftar pendaftar |
| PATCH | `/api/pendaftaran/:id/status` | Divisi | Update status pendaftar |
| GET | `/api/mahasiswa/pendaftaran` | Mahasiswa | Riwayat pendaftaran mahasiswa |
| GET/PUT | `/api/profil` | Mahasiswa/Divisi | Lihat/edit profil |

---

## 12. Kriteria Keberhasilan (Definition of Done)

- Divisi dapat memposting lowongan magang dan mahasiswa dapat mendaftar tanpa error
- Alur pendaftaran berjalan lengkap dari posting → daftar → seleksi → diterima/ditolak
- Dashboard menampilkan data sesuai role pengguna (divisi lihat pendaftar, mahasiswa lihat status)
- Aplikasi dapat diakses melalui browser (web-based) dan responsif
- Data tersimpan persisten (menggunakan database) dan tidak hilang saat refresh

---