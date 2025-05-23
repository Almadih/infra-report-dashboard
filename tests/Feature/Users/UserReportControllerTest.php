<?php

namespace Tests\Feature\Users;

use App\Models\DamageType;
use App\Models\Report;
use App\Models\Severity;
use App\Models\User;
use Clickbar\Magellan\Data\Geometries\Point;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\get;
use function Pest\Laravel\post;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(\Database\Seeders\DamageTypeSeeder::class);
    $this->seed(\Database\Seeders\SeveritySeeder::class);
    $this->seed(\Database\Seeders\StatusSeeder::class);

    $user = User::factory()->create([
        'is_anonymous' => true,
    ]);
    $this->actingAs($user, 'sanctum');

    Storage::fake('private');
});

it('can get reports within radius', function () {
    $damageType = DamageType::first();
    $severity = Severity::first();

    $report = Report::factory()->create([
        'damage_type_id' => $damageType->id,
        'severity_id' => $severity->id,
        'location' => Point::makeGeodetic(10.0, 30.0),
    ]);

    $response = get(route('api.reports.index', [
        'lng' => 30.0,
        'lat' => 10.0,
        'radius' => 1000,
    ]));

    $response->assertStatus(200)
        ->assertJsonFragment([
            'id' => $report->id,
        ]);
});

it('validates store request data', function () {

    $response = $this->postJson(route('api.reports.store'), []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors([
            'damage_type_id',
            'severity_id',
            'images',
            'location.lat',
            'location.lng',
            'address',
        ]);
});

it('can store a new report with images', function () {
    $damageType = DamageType::first();
    $severity = Severity::first();

    $images = [
        UploadedFile::fake()->image('photo1.jpg'),
        UploadedFile::fake()->image('photo2.jpg'),
    ];

    $postData = [
        'damage_type_id' => $damageType->id,
        'severity_id' => $severity->id,
        'images' => $images,
        'location' => [
            'lat' => 10.0,
            'lng' => 30.0,
        ],
        'address' => '123 Test St',
        'description' => 'Test description',
    ];

    $response = post(route('api.reports.store'), $postData);

    $response->assertStatus(200)
        ->assertJsonFragment([
            'address' => '123 Test St',
            'description' => 'Test description',
            'damage_type_id' => $damageType->id,
            'severity_id' => $severity->id,
        ]);

    $reportId = $response->json('id');

    $this->assertDatabaseHas('reports', [
        'id' => $reportId,
        'address' => '123 Test St',
    ]);

    foreach ($images as $image) {
        Storage::disk('private')->assertExists("reports/{$reportId}/".$image->hashName());
    }
});
