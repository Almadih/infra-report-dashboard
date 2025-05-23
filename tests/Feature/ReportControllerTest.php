<?php

use App\Models\Report;
use App\Models\Severity;
use App\Models\Status;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\get;
use function Pest\Laravel\put;

uses(RefreshDatabase::class, WithFaker::class);

beforeEach(function () {
    // Seed necessary data for statuses and severities
    $this->seed(\Database\Seeders\StatusSeeder::class);
    $this->seed(\Database\Seeders\SeveritySeeder::class);

    // Create and authenticate a user for all requests
    $user = \App\Models\User::factory()->create();
    $this->actingAs($user);
});

it('can view the reports index with default filters', function () {
    $response = get(route('reports.index'));

    $response->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page->component('Reports/Index')
            ->has('reports')
            ->has('filters')
            ->has('center')
        );
});

it('can filter reports by severity and status', function () {
    $criticalSeverity = Severity::where('name', 'critical')->first();
    $pendingStatus = Status::where('name', 'pending')->first();

    $report = Report::factory()->create([
        'severity_id' => $criticalSeverity->id,
        'status_id' => $pendingStatus->id,
    ]);

    $response = get(route('reports.index', [
        'critical' => 'true',
        'high' => 'false',
        'medium' => 'false',
        'low' => 'false',
        'pending' => 'true',
        'under_review' => 'false',
        'verified' => 'false',
        'resolved' => 'false',
    ]));

    $response->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page->component('Reports/Index')
            ->where('filters.severity.critical', true)
            ->where('filters.status.pending', true)
            ->where('filters.severity.high', false)
            ->where('filters.status.verified', false)
            ->has('reports.data', 1)
        );
});

it('can show a single report', function () {
    $report = Report::factory()->create();

    $response = get(route('reports.show', $report));

    $response->assertStatus(200)
        ->assertInertia(fn (Assert $page) => $page->component('Reports/Show')
            ->has('report')
            ->has('statuses')
        );
});

it('can update a report status', function () {
    $report = Report::factory()->create();
    $newStatus = Status::factory()->create();

    $response = put(route('reports.update', $report), [
        'status_id' => $newStatus->id,
    ]);

    $response->assertRedirect(route('reports.show', $report->id));
    $this->assertDatabaseHas('reports', [
        'id' => $report->id,
        'status_id' => $newStatus->id,
    ]);
});

it('fails to update report status with invalid data', function () {
    $report = Report::factory()->create();

    $response = put(route('reports.update', $report), [
        'status_id' => 999999, // non-existent status id
    ]);

    $response->assertSessionHasErrors('status_id');
});
