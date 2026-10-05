<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('registration creates a Sanctum access token', function () {
    $response = $this->postJson('/api/auth/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password123',
    ]);

    $response->assertCreated()
        ->assertJsonStructure([
            'token',
            'data' => ['id', 'email'],
        ]);

    $this->assertDatabaseCount('personal_access_tokens', 1);
});
