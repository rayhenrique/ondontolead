<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clinic_blocked_dates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained('clinics')->cascadeOnDelete();
            $table->date('blocked_date');
            $table->string('reason', 150)->nullable();
            $table->timestamps();

            $table->unique(['clinic_id', 'blocked_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clinic_blocked_dates');
    }
};
