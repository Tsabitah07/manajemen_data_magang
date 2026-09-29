# RuangMagang

RuangMagang adalah proyek platform informasi dan manajemen magang kampus yang ditujukan untuk membantu mahasiswa menemukan peluang magang serta membantu divisi kampus mengelola proses rekrutmen. Repository ini berisi frontend Vue dan backend API Go.

[//]: # (> **Status saat ini:** yang sudah dapat dicoba adalah landing page responsif berbahasa Indonesia. Login, pendaftaran akun, lowongan, dashboard, dan proses seleksi masih berupa rencana produk atau belum terhubung ke backend.)

## Teknologi

| Bagian | Teknologi |
| --- | --- |
| Frontend | Vue 3, TypeScript, Vite |
| Routing | Vue Router |
| State management | Pinia |
| Styling | Tailwind CSS dan CSS |
| Backend | Go, Gin |
| Konfigurasi backend | godotenv |


## Menjalankan frontend secara lokal

### Prasyarat

- Node.js `22.18+` atau `24.12+`
- npm

### Langkah

Dari root repository:

```sh
cd frontend
npm ci
npm run dev
```

Buka URL yang dicetak Vite di terminal (biasanya <http://localhost:5173>). Untuk menghentikan server, tekan `Ctrl+C`.

[//]: # ()
[//]: # (Perintah frontend lainnya, jalankan dari direktori `frontend`:)

[//]: # ()
[//]: # (```sh)

[//]: # (npm run build       # type-check dan build untuk produksi)

[//]: # (npm run test:unit   # jalankan unit test dengan Vitest)

[//]: # (npm run preview     # sajikan hasil build secara lokal)

[//]: # (```)

[//]: # ()
[//]: # (## Menjalankan backend lokal &#40;opsional&#41;)

[//]: # ()
[//]: # (Backend saat ini berupa kerangka API Gin. Prasyaratnya adalah Go yang mendukung versi pada `backend/go.mod` &#40;Go `1.27`&#41; dan koneksi internet saat Go mengunduh modul untuk pertama kali.)

[//]: # ()
[//]: # (`backend/initializers/loadEnv.go` mewajibkan file `.env` ketika server dijalankan. Buat file kosong `backend/.env` jika belum ada. Dari root repository, misalnya di PowerShell:)

[//]: # ()
[//]: # (```powershell)

[//]: # (New-Item -ItemType File -Path backend\.env)

[//]: # (```)

[//]: # ()
[//]: # (Kemudian jalankan backend dari direktori `backend`:)

[//]: # ()
[//]: # (```sh)

[//]: # (cd backend)

[//]: # (go run .)

[//]: # (```)

[//]: # ()
[//]: # (Server Gin mendengarkan di <http://localhost:8080> secara default. Variabel `PORT` yang mungkin ada di `.env` belum digunakan oleh server.)

[//]: # ()
[//]: # (Endpoint yang tersedia saat ini hanya contoh stub:)

[//]: # ()
[//]: # (| Method | Endpoint | Respons saat ini |)

[//]: # (| --- | --- | --- |)

[//]: # (| `GET` | `/api/login` | JSON dengan pesan `Login Success` |)

[//]: # (| `POST` | `/api/register` | JSON dengan pesan `Register Success` |)

[//]: # ()
[//]: # (Endpoint tersebut belum memvalidasi kredensial atau menyimpan data. Konfigurasi CORS backend mengizinkan origin `http://localhost:5173` dan `http://localhost:8080`, tetapi frontend belum memanggil endpoint tersebut.)


## Struktur repository


```text

├── backend/
│   ├── controller/       # Handler endpoint Gin
│   ├── initializers/     # Pemuatan konfigurasi
│   ├── models/           # Model data Go
│   ├── services/         # Lapisan service
│   ├── main.go           # Setup server dan route API
│   └── go.mod
├── frontend/
│   ├── src/
│   │   ├── components/landing_pages/
│   │   ├── router/
│   │   └── views/
│   ├── package.json
│   └── vite.config.ts
└── Pemaparan_Ide.md
└── PRD_Sistem_Manajemen_Magang_Kampus.md
└── README.md

```

[//]: # ()
[//]: # (## Dokumen)

[//]: # ()
[//]: # (- [Product Requirements Document]&#40;PRD_Sistem_Manajemen_Magang_Kampus.md&#41; — tujuan, ruang lingkup, kebutuhan fitur, dan rancangan sistem.)

[//]: # (- [Pemaparan Ide]&#40;Pemaparan_Ide.md&#41; — dokumen pemaparan ide produk.)
