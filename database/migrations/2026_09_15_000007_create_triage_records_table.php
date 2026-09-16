<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('triage_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->unique()->constrained('appointments')->cascadeOnDelete();
            $table->text('raw_complaint');
            $table->unsignedTinyInteger('pain_level')->default(0);
            $table->enum('urgency_level', ['low', 'medium', 'high'])->default('low');
            $table->string('suggested_procedure', 150)->nullable();
            $table->text('ai_summary')->nullable();
            $table->boolean('processed_by_ai')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('triage_records');
    }
};
