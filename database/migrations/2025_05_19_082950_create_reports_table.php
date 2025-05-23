<?php

use App\Models\DamageType;
use App\Models\Severity;
use App\Models\Status;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignIdFor(Severity::class, 'severity_id')->constrained();
            $table->foreignIdFor(Status::class, 'status_id')->constrained();
            $table->foreignIdFor(DamageType::class, 'damage_type_id')->constrained();
            $table->magellanPoint('location');
            $table->text('description')->nullable();
            $table->string('address');
            $table->index(['severity_id', 'damage_type_id', 'status_id'], 'idx_reports_sev_type_status');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
