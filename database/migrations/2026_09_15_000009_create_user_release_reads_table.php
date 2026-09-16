<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_release_reads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('app_release_id')->constrained('app_releases')->cascadeOnDelete();
            $table->timestamp('read_at');

            $table->unique(['user_id', 'app_release_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_release_reads');
    }
};
