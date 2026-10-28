<?php

namespace Database\Seeders;

use App\Models\CampusUnit;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'id' => 'admin-001',
                'name' => 'Admin User',
                'phone' => '081234567000',
                'email' => 'admin@campus.test',
                'password' => 'password',
                'role' => 'admin',
            ],
            [
                'id' => 'user-001',
                'name' => 'Rina Sari',
                'phone' => '081234567001',
                'email' => 'rina.sari@campus.test',
                'password' => 'password',
                'role' => 'campus-unit',
            ],
            [
                'id' => 'user-002',
                'name' => 'Dimas Pratama',
                'phone' => '081234567002',
                'email' => 'dimas.pratama@campus.test',
                'password' => 'password',
                'role' => 'campus-unit',
            ],
            [
                'id' => 'user-003',
                'name' => 'Ayu Lestari',
                'phone' => '081234567003',
                'email' => 'ayu.lestari@campus.test',
                'password' => 'password',
                'role' => 'campus-unit',
            ],
            [
                'id' => 'user-004',
                'name' => 'Fajar Nugroho',
                'phone' => '081234567004',
                'email' => 'fajar.nugroho@campus.test',
                'password' => 'password',
                'role' => 'campus-unit',
            ],
            [
                'id' => 'user-005',
                'name' => 'Sita Ramadhani',
                'phone' => '081234567005',
                'email' => 'sita.ramadhani@campus.test',
                'password' => 'password',
                'role' => 'student',
            ],
            [
                'id' => 'user-006',
                'name' => 'Budi Santoso',
                'phone' => '081234567006',
                'email' => 'budi.santoso@campus.test',
                'password' => 'password',
                'role' => 'student',
            ],
            [
                'id' => 'user-007',
                'name' => 'Nadia Putri',
                'phone' => '081234567007',
                'email' => 'nadia.putri@campus.test',
                'password' => 'password',
                'role' => 'student',
            ],
            [
                'id' => 'user-008',
                'name' => 'Rizky Maulana',
                'phone' => '081234567008',
                'email' => 'rizky.maulana@campus.test',
                'password' => 'password',
                'role' => 'student',
            ],
            [
                'id' => 'user-009',
                'name' => 'Intan Permata',
                'phone' => '081234567009',
                'email' => 'intan.permata@campus.test',
                'password' => 'password',
                'role' => 'student',
            ],
        ];

        foreach ($users as $userData) {
            User::firstOrCreate(
                ['email' => $userData['email']],
                $userData,
            );
        }

        $campusUnits = [
            ['user_id' => 'user-001', 'unit_name' => 'Academic Affairs', 'position' => 'Head of Unit'],
            ['user_id' => 'user-002', 'unit_name' => 'Student Affairs', 'position' => 'Coordinator'],
            ['user_id' => 'user-003', 'unit_name' => 'Research and Development', 'position' => 'Staff'],
            ['user_id' => 'user-004', 'unit_name' => 'Information Technology', 'position' => 'Deputy Head of Unit'],
        ];

        foreach ($campusUnits as $campusUnit) {
            CampusUnit::firstOrCreate(
                ['user_id' => $campusUnit['user_id']],
                $campusUnit,
            );
        }

        $students = [
            [
                'user_id' => 'user-005',
                'gender' => 'female',
                'nim' => '20240001',
                'major' => 'Informatics Engineering',
                'status' => 'active',
                'enrollment_year' => 2024,
            ],
            [
                'user_id' => 'user-006',
                'gender' => 'male',
                'nim' => '20230002',
                'major' => 'Information Systems',
                'status' => 'active',
                'enrollment_year' => 2023,
            ],
            [
                'user_id' => 'user-007',
                'gender' => 'female',
                'nim' => '20240003',
                'major' => 'Computer Science',
                'status' => 'active',
                'enrollment_year' => 2024,
            ],
            [
                'user_id' => 'user-008',
                'gender' => 'male',
                'nim' => '20220004',
                'major' => 'Information Technology',
                'status' => 'active',
                'enrollment_year' => 2022,
            ],
            [
                'user_id' => 'user-009',
                'gender' => 'female',
                'nim' => '20250005',
                'major' => 'Informatics Engineering',
                'status' => 'active',
                'enrollment_year' => 2025,
            ],
        ];

        foreach ($students as $studentData) {
            Student::firstOrCreate(
                ['nim' => $studentData['nim']],
                $studentData,
            );
        }
    }
}
