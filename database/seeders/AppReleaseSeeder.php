<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppReleaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $timestamp = now();

        DB::table('app_releases')->upsert([
            [
                'version' => 'v1.0.0',
                'title' => 'Primeira versão do OdontoLead AI',
                'content' => <<<'MARKDOWN'
                    ## OdontoLead AI v1.0.0

                    Primeira versão do MVP com captação, triagem e agendamento de leads para clínicas odontológicas.
                    MARKDOWN,
                'show_modal' => true,
                'released_at' => '2026-09-15 00:00:00',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
        ], ['version'], [
            'title',
            'content',
            'show_modal',
            'released_at',
            'updated_at',
        ]);
    }
}
