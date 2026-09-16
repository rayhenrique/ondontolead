<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clinics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained('plans');
            $table->string('name', 150);
            $table->string('slug', 100)->unique();
            $table->string('whatsapp_number', 20);
            $table->enum('ai_provider', ['none', 'gemini', 'openai'])->default('none');
            $table->text('ai_api_key')->nullable();
            $table->enum('subscription_status', ['trial', 'active', 'past_due', 'canceled'])->default('trial');
            $table->timestamp('trial_ends_at')->nullable();
            $table->string('mp_subscription_id', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clinics');
    }
};
