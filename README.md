# RuangMagang

RuangMagang adalah platform manajemen magang internal kampus yang menghubungkan mahasiswa dengan divisi/organisasi kampus. Aplikasi menyediakan publikasi lowongan, pendaftaran, seleksi, profil pengguna, dashboard berdasarkan peran, serta notifikasi status.

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

### 1. Siapkan database dan backend

Buat database MySQL, misalnya `ruangmagang`:

```sql
CREATE DATABASE ruangmagang CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Lalu buat konfigurasi lokal dari contoh:

```powershell
Copy-Item backend\.env.example backend\.env
```

Isi `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` di `backend/.env` sesuai database MySQL milikmu. Jangan membagikan atau memasukkan `backend/.env` ke Git.

Instal dependensi, buat kunci aplikasi dan secret JWT, lalu jalankan migrasi dari direktori backend:

```powershell
Set-Location backend
composer install
php artisan key:generate
php artisan jwt:secret
php artisan migrate
php artisan serve
```

API berjalan di <http://localhost:8000> secara default (route berada di bawah prefix `/api`). Skema database dikelola melalui migration di `database/migrations/`.

Untuk membuat akun demo, jalankan perintah ini dari `backend/` setelah konfigurasi `.env` siap:

```powershell
php artisan db:seed
```

Login demo: `rina.sari@campus.test` / `password`. Seeder hanya berjalan jika dipanggil secara eksplisit; kredensial ini khusus pengembangan lokal. Atur `SEED_USER_NAME`, `SEED_USER_EMAIL`, dan `SEED_USER_PASSWORD` di `.env` untuk menggantinya.

### 2. Jalankan frontend

Buka terminal kedua dari root repository:

```powershell
Set-Location frontend
npm install
Copy-Item .env.example .env
npm run dev
```

Buka URL Vite yang muncul di terminal (biasanya <http://localhost:5173>). Variabel `VITE_API_BASE_URL` pada `frontend/.env` dapat diarahkan ke base URL REST API lain (default: `http://localhost:8000/api`). Pastikan origin frontend terdaftar di `config/cors.php` pada backend.

## Fitur

- Registrasi dan login untuk mahasiswa maupun divisi kampus.
- Pembuatan, pengeditan, dan penghapusan lowongan oleh divisi pemilik.
- Pencarian lowongan dan pendaftaran mahasiswa, termasuk pembatalan selama status masih menunggu.
- Dashboard divisi untuk mengelola lowongan, meninjau pendaftar, serta menerima atau menolak pendaftaran.
- Dashboard mahasiswa untuk melacak status pendaftaran dan notifikasi.
- Profil mahasiswa (NIM, jurusan, kontak, dan portofolio) serta profil divisi.

## Perintah pengembangan

Dari `frontend/`:

```sh
npm run dev
npm run build       # type-check dan production build
npm run test:unit   # unit test Vitest
```

Dari `backend/`:

```sh
php artisan migrate
php artisan db:seed
php artisan test
```

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