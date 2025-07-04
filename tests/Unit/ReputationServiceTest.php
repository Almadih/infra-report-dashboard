<?php

namespace Tests\Unit;

use App\Models\Report;
use App\Models\User;
use App\Services\ReputationService;

beforeEach(function () {
    $this->user = User::factory()->create(['reputation' => 0]);
    $this->report = Report::factory()->create(['user_id' => $this->user->id]);

});

it('adds reputation history for valid record type', function () {

    ReputationService::addReputationHistory($this->report, ReputationService::TYPE_SUBMIT);

    $this->assertDatabaseHas('reputation_histories', [
        'report_id' => $this->report->id,
        'type' => ReputationService::TYPE_SUBMIT,
        'amount' => 5,
    ]);
    $this->user->refresh();
    $this->assertEquals(5, $this->user->reputation);
});

it('throws exception for invalid record type', function () {
    ReputationService::addReputationHistory($this->report, 'invalid_type');
})->throws(\InvalidArgumentException::class);

it('updates user reputation correctly', function () {
    ReputationService::addReputationHistory($this->report, ReputationService::TYPE_VERIFIED);

    $this->user->refresh();
    $this->assertEquals(15, $this->user->reputation);
});
