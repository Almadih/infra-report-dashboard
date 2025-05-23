<?php

use App\Models\DamageType;
use App\Models\Report;
use App\Models\Severity;
use App\Models\Status;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('returns reports for the authenticated user with related data', function () {
    // Create a user
    $user = User::factory()->create();

    // Create related models
    $status = Status::factory()->create();
    $damageType = DamageType::factory()->create();
    $severity = Severity::factory()->create();

    // Create reports for the user with relations
    $reports = Report::factory()
        ->count(3)
        ->for($user, 'user')
        ->for($status, 'status')
        ->for($damageType, 'damageType')
        ->for($severity, 'severity')
        ->hasImages(2)
        ->create();

    // Authenticate as the user
    $this->actingAs($user, 'sanctum');

    // Call the route
    $response = $this->getJson(route('my-reports'));

    // Assert response status
    $response->assertOk();

    // Assert JSON structure and data count
    $response->assertJsonCount(3);

    $response->assertJson(fn ($json) => $json->each(fn ($report) => $report->where('user_id', $user->id)
        ->has('status')
        ->has('damage_type')
        ->has('severity')
        ->has('images')
        ->etc()
    )
    );
});
