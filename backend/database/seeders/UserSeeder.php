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
                'id' => 'user-001',
                'name' => 'Rina Sari',
                'phone' => '081234567001',
                'email' => 'rina.sari@campus.test',
                'password' => 'password',
            ],
            [
                'id' => 'user-002',
                'name' => 'Dimas Pratama',
                'phone' => '081234567002',
                'email' => 'dimas.pratama@campus.test',
                'password' => 'password',
            ],
            [
                'id' => 'user-003',
                'name' => 'Ayu Lestari',
                'phone' => '081234567003',
                'email' => 'ayu.lestari@campus.test',
                'password' => 'password',
            ],
            [
                'id' => 'user-004',
                'name' => 'Fajar Nugroho',
                'phone' => '081234567004',
                'email' => 'fajar.nugroho@campus.test',
                'password' => 'password',
            ],
            [
                'id' => 'user-005',
                'name' => 'Sita Ramadhani',
                'phone' => '081234567005',
                'email' => 'sita.ramadhani@campus.test',
                'password' => 'password',
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

        Student::firstOrCreate(
            ['nim' => '20240001'],
            [
                'user_id' => 'user-005',
                'gender' => 'female',
                'nim' => '20240001',
                'major' => 'Informatics Engineering',
                'status' => 'active',
                'enrollment_year' => 2024,
            ],
        );
    }
}
