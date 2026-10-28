<?php

use App\Models\CampusUnit;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('students can retrieve and update their detailed profile', function () {
    $student = User::factory()->create(['role' => 'student']);
    Student::create([
        'user_id' => $student->id,
        'nim' => '20240001',
        'major' => 'Informatics Engineering',
    ]);

    $this->actingAs($student, 'sanctum')
        ->getJson('/api/auth/profile')
        ->assertOk()
        ->assertJsonPath('student_profile.nim', '20240001');

    $this->actingAs($student, 'sanctum')
        ->putJson('/api/auth/update', [
            'name' => 'Updated Student',
            'nim' => '20240002',
            'major' => 'Computer Science',
            'gender' => 'female',
            'enrollment_year' => 2024,
        ])
        ->assertOk()
        ->assertJsonPath('data.student_profile.nim', '20240002')
        ->assertJsonPath('data.student_profile.major', 'Computer Science');

    $this->assertDatabaseHas('students', [
        'user_id' => $student->id,
        'nim' => '20240002',
        'major' => 'Computer Science',
        'gender' => 'female',
        'enrollment_year' => 2024,
    ]);
});

test('students without a detail record can complete their profile', function () {
    $student = User::factory()->create(['role' => 'student']);

    $this->actingAs($student, 'sanctum')
        ->putJson('/api/auth/update', [
            'name' => 'New Student',
            'nim' => '20240003',
            'major' => 'Information Systems',
        ])
        ->assertOk()
        ->assertJsonPath('data.student_profile.nim', '20240003')
        ->assertJsonPath('data.student_profile.status', 'active');

    $this->assertDatabaseHas('students', [
        'user_id' => $student->id,
        'nim' => '20240003',
        'major' => 'Information Systems',
        'status' => 'active',
    ]);
});

test('campus units can retrieve and update their detailed profile', function () {
    $campusUnit = User::factory()->create(['role' => 'campus-unit']);
    CampusUnit::create([
        'user_id' => $campusUnit->id,
        'unit_name' => 'Academic Affairs',
        'position' => 'Staff',
    ]);

    $this->actingAs($campusUnit, 'sanctum')
        ->getJson('/api/auth/profile')
        ->assertOk()
        ->assertJsonPath('campus_unit_profile.unit_name', 'Academic Affairs');

    $this->actingAs($campusUnit, 'sanctum')
        ->putJson('/api/auth/update', [
            'name' => 'Updated Unit Contact',
            'unit_name' => 'Student Affairs',
            'position' => 'Coordinator',
        ])
        ->assertOk()
        ->assertJsonPath('data.campus_unit_profile.unit_name', 'Student Affairs')
        ->assertJsonPath('data.campus_unit_profile.position', 'Coordinator');

    $this->assertDatabaseHas('campus_units', [
        'user_id' => $campusUnit->id,
        'unit_name' => 'Student Affairs',
        'position' => 'Coordinator',
    ]);
});
