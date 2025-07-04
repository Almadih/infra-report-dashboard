<?php

use App\Models\Report;
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
        Schema::create('report_flags', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('report_id')->for(Report::class)->constrained()->onDelete('cascade');
            $table->foreignUuid('duplicated_report_id')->for(Report::class)->nullable()->onDelete('cascade');
            $table->enum('type', ['duplicate', 'low_quality']);
            $table->string('reason')->nullable();
            $table->boolean('auto_flagged')->default(true);
            $table->boolean('confirmed_by_admin')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_flags');
    }
};
