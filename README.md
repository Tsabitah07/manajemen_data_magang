# RuangMagang

RuangMagang adalah platform manajemen magang internal kampus yang menghubungkan mahasiswa dengan divisi/organisasi kampus. Aplikasi menyediakan publikasi lowongan, pendaftaran, seleksi, profil pengguna, dashboard berdasarkan peran, serta notifikasi status.

Video Demo: https://drive.google.com/file/d/13_WkM8jmEzFlLTuVaSpEPfpospi9f_T6/view?usp=sharing

## Teknologi

| Bagian | Teknologi |
| --- | --- |
| Frontend | Vue 3, TypeScript, Vite, Pinia, Vue Router, Axios |
| Backend | PHP 8.2+, Laravel, Eloquent ORM |
| Database | MySQL 8 |
| Autentikasi | JWT (`php-open-source-saver/jwt-auth`) dan bcrypt |

## Menjalankan aplikasi

### Prasyarat

- Node.js `22.18+` atau `24.12+` dan npm
- PHP `8.2+` dengan ekstensi `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, dan `ctype`
- Composer
- MySQL `8.x`

### Perintah pengembangan

Dari `frontend/`:

```sh
npm install
npm run dev
npm run build       # type-check dan production build
npm run test:unit   # unit test Vitest
```

Dari `backend/`:

```sh
php artisan migrate
php artisan db:seed
php artisan storage:link  #to access image / documents
php artisan test
```

## Fitur

- Registrasi dan login untuk mahasiswa maupun divisi kampus.
- Pembuatan, pengeditan, dan penghapusan lowongan oleh divisi pemilik.
- Pencarian lowongan dan pendaftaran mahasiswa, termasuk pembatalan selama status masih menunggu.
- Dashboard divisi untuk mengelola lowongan, meninjau pendaftar, serta menerima atau menolak pendaftaran.
- Dashboard mahasiswa untuk melacak status pendaftaran dan notifikasi.
- Profil mahasiswa (NIM, jurusan, kontak, dan portofolio) serta profil divisi.

## Struktur repository

```text
backend/
├── app/
│   ├── Http/
│   │   ├── Controllers/  # HTTP controllers Laravel
│   │   ├── Middleware/   # Validasi JWT dan otorisasi role
│   │   ├── Requests/     # Form Request (validasi input)
│   │   └── Resources/    # Transformasi respons JSON
│   ├── Models/           # Model Eloquent
│   ├── Repositories/     # Akses data MySQL
│   └── Services/         # Aturan bisnis
├── bootstrap/            # Bootstrap aplikasi dan registrasi middleware
├── config/               # Konfigurasi (database, jwt, cors)
├── database/
│   ├── factories/
│   ├── migrations/       # Skema database MySQL
│   └── seeders/          # Seeder modular untuk model
├── routes/
│   └── api.php           # Registrasi REST routes
├── tests/                # Feature & unit test
└── .env.example
frontend/
├── src/
│   ├── components/       # Komponen reusable dan landing page
│   ├── router/           # Routes dan route guard
│   ├── services/         # REST API client dan types
│   ├── stores/           # State autentikasi Pinia
│   └── views/            # Landing, autentikasi, lowongan, dashboard, profil
└── .env.example
```

Dokumen lengkap kebutuhan produk: [PRD Sistem Manajemen Magang Kampus](PRD_Sistem_Manajemen_Magang_Kampus.md).