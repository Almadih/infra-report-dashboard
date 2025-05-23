<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Setup before each test if needed
});

it('returns validation error if device_id is missing', function () {
    $response = $this->postJson(route('anonymous.auth'), []);
    $response->assertStatus(422);
    $response->assertJsonValidationErrors('device_id');
});

it('returns existing user and token if user with device_id exists', function () {
    $deviceId = 'existing-device-123';
    $user = User::factory()->create([
        'device_id' => $deviceId,
        'is_anonymous' => true,
    ]);

    $response = $this->postJson(route('anonymous.auth'), [
        'device_id' => $deviceId,
    ]);

    $response->assertStatus(200);
    $response->assertJson([
        'message' => 'Device initiated successfully.',
        'user' => [
            'id' => $user->id,
            'device_id' => $deviceId,
            'is_anonymous' => true,
        ],
    ]);
    $this->assertArrayHasKey('access_token', $response->json());
});

it('creates new anonymous user if none exists and returns token', function () {
    $deviceId = 'new-device-456';

    $response = $this->postJson(route('anonymous.auth'), [
        'device_id' => $deviceId,
    ]);

    $response->assertStatus(200);
    $response->assertJson([
        'message' => 'Device initiated successfully.',
        'user' => [
            'device_id' => $deviceId,
            'is_anonymous' => true,
        ],
    ]);
    $this->assertArrayHasKey('access_token', $response->json());

    $this->assertDatabaseHas('users', [
        'device_id' => $deviceId,
        'is_anonymous' => true,
    ]);
});
