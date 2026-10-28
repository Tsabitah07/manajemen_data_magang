<?php

namespace Database\Seeders;

use App\Models\Internship;
use Illuminate\Database\Seeder;

class InternshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $internships = [
            [
                'id' => 'IN-SEED-001',
                'title' => 'Pengembangan Aplikasi Layanan Akademik',
                'description' => 'Mendukung pengembangan dan pengujian aplikasi layanan akademik kampus bersama tim Information Technology.',
                'start_date' => '2027-01-04',
                'end_date' => '2027-06-30',
                'quota' => 3,
                'documentation' => null,
                'campus_unit_id' => 'user-004',
                'is_open' => true,
                'cv_required' => true,
            ],
            [
                'id' => 'IN-SEED-002',
                'title' => 'Analisis Data Penelitian Kampus',
                'description' => 'Membantu tim Research and Development mengolah data penelitian, menyusun visualisasi, dan menyiapkan laporan.',
                'start_date' => '2027-02-01',
                'end_date' => '2027-07-30',
                'quota' => 2,
                'documentation' => null,
                'campus_unit_id' => 'user-003',
                'is_open' => true,
                'cv_required' => false
            ],
            [
                'id' => 'IN-SEED-003',
                'title' => 'Administrasi dan Layanan Kemahasiswaan',
                'description' => 'Mendukung pencatatan layanan, pengelolaan berkas, dan penyusunan laporan kegiatan Student Affairs.',
                'start_date' => '2026-08-03',
                'end_date' => '2026-12-31',
                'quota' => 2,
                'documentation' => null,
                'campus_unit_id' => 'user-002',
                'is_open' => false,
                'cv_required' => false
            ],
            [
                'id' => 'IN-SEED-004',
                'title' => 'Pengelolaan Infrastruktur Jaringan Kampus',
                'description' => 'Membantu tim Information Technology memantau jaringan kampus, mendokumentasikan aset, dan mendukung pemeliharaan infrastruktur.',
                'start_date' => '2027-01-11',
                'end_date' => '2027-06-30',
                'quota' => 2,
                'documentation' => null,
                'campus_unit_id' => 'user-004',
                'is_open' => true,
                'cv_required' => true
            ],
            [
                'id' => 'IN-SEED-005',
                'title' => 'Desain Konten Informasi Akademik',
                'description' => 'Membuat materi visual dan membantu penyusunan konten informasi akademik untuk mahasiswa.',
                'start_date' => '2027-01-18',
                'end_date' => '2027-05-31',
                'quota' => 2,
                'documentation' => null,
                'campus_unit_id' => 'user-001',
                'is_open' => true,
                'cv_required' => false
            ],
            [
                'id' => 'IN-SEED-006',
                'title' => 'Pengembangan Dashboard Data Penelitian',
                'description' => 'Membantu membuat dashboard untuk merangkum data penelitian dan capaian publikasi kampus.',
                'start_date' => '2027-02-08',
                'end_date' => '2027-07-30',
                'quota' => 2,
                'documentation' => null,
                'campus_unit_id' => 'user-003',
                'is_open' => true,
                'cv_required' => true
            ],
            [
                'id' => 'IN-SEED-007',
                'title' => 'Pengelolaan Basis Data Akademik',
                'description' => 'Mendukung validasi data akademik, penyusunan dokumentasi, dan pembuatan laporan untuk kebutuhan unit akademik.',
                'start_date' => '2027-01-25',
                'end_date' => '2027-06-25',
                'quota' => 2,
                'documentation' => null,
                'campus_unit_id' => 'user-001',
                'is_open' => true,
                'cv_required' => false
            ],
            [
                'id' => 'IN-SEED-008',
                'title' => 'Program Pendampingan Kegiatan Mahasiswa',
                'description' => 'Membantu koordinasi kegiatan mahasiswa, pencatatan pengajuan, dan evaluasi program kemahasiswaan.',
                'start_date' => '2027-02-01',
                'end_date' => '2027-06-30',
                'quota' => 3,
                'documentation' => null,
                'campus_unit_id' => 'user-002',
                'is_open' => true,
                'cv_required' => true
            ],
            [
                'id' => 'IN-SEED-009',
                'title' => 'Dokumentasi dan Publikasi Riset Kampus',
                'description' => 'Menyusun ringkasan hasil riset dan membantu publikasi informasi penelitian kepada sivitas akademika.',
                'start_date' => '2027-03-01',
                'end_date' => '2027-07-30',
                'quota' => 1,
                'documentation' => null,
                'campus_unit_id' => 'user-003',
                'is_open' => true,
                'cv_required' => false
            ],
            [
                'id' => 'IN-SEED-010',
                'title' => 'Dukungan Sistem Informasi Kemahasiswaan',
                'description' => 'Mendukung pengujian fitur sistem informasi kemahasiswaan dan membantu menyusun panduan penggunaan.',
                'start_date' => '2027-01-11',
                'end_date' => '2027-06-30',
                'quota' => 2,
                'documentation' => null,
                'campus_unit_id' => 'user-002',
                'is_open' => true,
                'cv_required' => false
            ],
        ];

        foreach ($internships as $internshipData) {
            Internship::firstOrCreate(
                [
                    'title' => $internshipData['title'],
                    'campus_unit_id' => $internshipData['campus_unit_id'],
                ],
                $internshipData,
            );
        }
    }
}
