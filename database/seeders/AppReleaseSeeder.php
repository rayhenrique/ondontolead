<?php

namespace Database\Seeders;

use App\Services\ReleaseSyncService;
use Illuminate\Database\Seeder;

class AppReleaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(ReleaseSyncService $syncService): void
    {
        $syncService->sync();
    }
}
