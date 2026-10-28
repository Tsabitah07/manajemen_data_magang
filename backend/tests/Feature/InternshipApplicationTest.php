<?php

use App\Models\Internship;
use App\Models\InternshipApplication;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('campus units can publish and retrieve their own internships', function () {
    $campusUnit = User::factory()->create(['role' => 'campus-unit']);

    $this->actingAs($campusUnit, 'sanctum')
        ->postJson('/api/internships', [
            'title' => 'Library Assistant',
            'description' => 'Support campus library activities.',
            'start_date' => '2026-11-01',
            'end_date' => '2027-01-01',
            'quota' => 2,
            'is_open' => true,
        ])
        ->assertCreated()
        ->assertJsonPath('data.campus_unit_id', $campusUnit->id);

    $this->getJson('/api/internships?campus_unit_id='.$campusUnit->id)
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.title', 'Library Assistant');
});

test('the campus unit that created an open internship can edit it', function () {
    $campusUnit = User::factory()->create(['role' => 'campus-unit']);
    $internship = Internship::create([
        'title' => 'Library Assistant',
        'description' => 'Support campus library activities.',
        'start_date' => '2026-11-01',
        'end_date' => '2027-01-01',
        'quota' => 2,
        'campus_unit_id' => $campusUnit->id,
        'is_open' => true,
    ]);

    $this->actingAs($campusUnit, 'sanctum')
        ->putJson("/api/internships?id={$internship->id}", [
            'title' => 'Updated Library Assistant',
            'description' => 'Updated responsibilities.',
            'start_date' => '2026-12-01',
            'end_date' => '2027-02-01',
            'quota' => 3,
            'cv_required' => true,
        ])
        ->assertOk()
        ->assertJsonPath('data.title', 'Updated Library Assistant')
        ->assertJsonPath('data.quota', 3)
        ->assertJsonPath('data.cv_required', true);
});

test('campus units can close and reopen their own internships', function () {
    $campusUnit = User::factory()->create(['role' => 'campus-unit']);
    $internship = Internship::create([
        'title' => 'Library Assistant',
        'description' => 'Support campus library activities.',
        'start_date' => '2026-11-01',
        'end_date' => '2027-01-01',
        'quota' => 2,
        'campus_unit_id' => $campusUnit->id,
        'is_open' => true,
    ]);

    $this->actingAs($campusUnit, 'sanctum')
        ->putJson("/api/internships?id={$internship->id}", ['is_open' => false])
        ->assertOk()
        ->assertJsonPath('data.is_open', false);

    $this->assertDatabaseHas('internships', [
        'id' => $internship->id,
        'is_open' => false,
    ]);

    $this->putJson("/api/internships?id={$internship->id}", ['is_open' => true])
        ->assertOk()
        ->assertJsonPath('data.is_open', true);

    $this->assertDatabaseHas('internships', [
        'id' => $internship->id,
        'is_open' => true,
    ]);
});

test('campus units cannot edit internships they did not create or that are closed', function () {
    $campusUnit = User::factory()->create(['role' => 'campus-unit']);
    $otherCampusUnit = User::factory()->create(['role' => 'campus-unit']);
    $openInternship = Internship::create([
        'title' => 'Open Internship',
        'description' => 'An open opportunity.',
        'start_date' => '2026-11-01',
        'end_date' => '2027-01-01',
        'quota' => 2,
        'campus_unit_id' => $otherCampusUnit->id,
        'is_open' => true,
    ]);
    $closedInternship = Internship::create([
        'title' => 'Closed Internship',
        'description' => 'A closed opportunity.',
        'start_date' => '2026-11-01',
        'end_date' => '2027-01-01',
        'quota' => 2,
        'campus_unit_id' => $campusUnit->id,
        'is_open' => false,
    ]);

    $this->actingAs($campusUnit, 'sanctum')
        ->putJson("/api/internships?id={$openInternship->id}", ['title' => 'Unauthorized update'])
        ->assertNotFound();

    $this->putJson("/api/internships?id={$closedInternship->id}", ['title' => 'Closed update'])
        ->assertNotFound();

    $this->assertDatabaseHas('internships', [
        'id' => $openInternship->id,
        'title' => 'Open Internship',
    ]);
    $this->assertDatabaseHas('internships', [
        'id' => $closedInternship->id,
        'title' => 'Closed Internship',
    ]);
});

test('students can submit an application and campus units can review it', function () {
    $student = User::factory()->create([
        'role' => 'student',
        'phone' => '08123456789',
        'profile_picture' => '/storage/images/student.png',
    ]);
    Student::create([
        'user_id' => $student->id,
        'nim' => '20240001',
        'major' => 'Informatics Engineering',
        'gender' => 'female',
        'enrollment_year' => 2024,
    ]);
    $campusUnit = User::factory()->create(['role' => 'campus-unit']);
    $internship = Internship::create([
        'title' => 'Library Assistant',
        'description' => 'Support campus library activities.',
        'start_date' => '2026-11-01',
        'end_date' => '2027-01-01',
        'quota' => 2,
        'campus_unit_id' => $campusUnit->id,
        'is_open' => true,
    ]);

    $this->actingAs($student, 'sanctum')
        ->postJson('/api/intern-application/student', [
            'internship_id' => $internship->id,
            'cv_required' => false,
        ])
        ->assertCreated()
        ->assertJsonPath('data.status', 'waiting');

    $applicationId = InternshipApplication::firstOrFail()->id;

    $this->getJson('/api/intern-application/student')
        ->assertOk()
        ->assertJsonPath('data.0.internship.title', 'Library Assistant');

    $this->actingAs($campusUnit, 'sanctum')
        ->getJson('/api/intern-application/campus-unit')
        ->assertOk()
        ->assertJsonPath('data.0.student.id', $student->id)
        ->assertJsonPath('data.0.student.profile_picture', '/storage/images/student.png')
        ->assertJsonPath('data.0.student.phone', '08123456789')
        ->assertJsonPath('data.0.student.student_profile.nim', '20240001')
        ->assertJsonPath('data.0.student.student_profile.major', 'Informatics Engineering')
        ->assertJsonPath('data.0.student.student_profile.gender', 'female')
        ->assertJsonPath('data.0.student.student_profile.enrollment_year', 2024);

    $this->putJson("/api/intern-application/campus-unit?id={$applicationId}", [
        'status' => 'accepted',
    ])->assertOk()->assertJsonPath('data.status', 'accepted');

    $this->assertDatabaseHas('internship_applications', [
        'id' => $applicationId,
        'status' => 'accepted',
    ]);
});

test('students must upload a CV when the internship requires it', function () {
    Storage::fake('public');

    $student = User::factory()->create(['role' => 'student']);
    $campusUnit = User::factory()->create(['role' => 'campus-unit']);
    $internship = Internship::create([
        'title' => 'Design Intern',
        'description' => 'Support the design team.',
        'start_date' => '2026-11-01',
        'end_date' => '2027-01-01',
        'quota' => 1,
        'campus_unit_id' => $campusUnit->id,
        'is_open' => true,
        'cv_required' => true,
    ]);

    $this->actingAs($student, 'sanctum')
        ->postJson('/api/intern-application/student', [
            'internship_id' => $internship->id,
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('cv');

    $this->actingAs($student, 'sanctum')
        ->post('/api/intern-application/student', [
            'internship_id' => $internship->id,
            'cv' => UploadedFile::fake()->create('resume.pdf', 100, 'application/pdf'),
        ])
        ->assertCreated()
        ->assertJsonPath('data.status', 'waiting');

    $application = InternshipApplication::firstOrFail();
    expect($application->cv)->not->toBeNull();
    Storage::disk('public')->assertExists(str_replace('/storage/', '', $application->cv));
});

test('students can withdraw a waiting application', function () {
    $student = User::factory()->create(['role' => 'student']);
    $campusUnit = User::factory()->create(['role' => 'campus-unit']);
    $internship = Internship::create([
        'title' => 'Library Assistant',
        'description' => 'Support campus library activities.',
        'start_date' => '2026-11-01',
        'end_date' => '2027-01-01',
        'quota' => 1,
        'campus_unit_id' => $campusUnit->id,
        'is_open' => true,
    ]);
    $application = InternshipApplication::create([
        'internship_id' => $internship->id,
        'student_id' => $student->id,
        'status' => 'waiting',
    ]);

    $this->actingAs($student, 'sanctum')
        ->putJson('/api/intern-application/student', [
            'id' => $application->id,
            'status' => 'cancelled',
        ])
        ->assertOk()
        ->assertJsonPath('data.status', 'cancelled');

    $this->assertDatabaseHas('internship_applications', [
        'id' => $application->id,
        'status' => 'cancelled',
    ]);
});
