<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $timestamp = now();

        DB::table('plans')->upsert([
            [
                'name' => 'Essencial',
                'slug' => 'essencial',
                'price' => 99.90,
                'max_appointments_per_month' => 100,
                'mp_plan_id' => null,
                'is_active' => true,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'name' => 'Profissional',
                'slug' => 'profissional',
                'price' => 199.90,
                'max_appointments_per_month' => 300,
                'mp_plan_id' => null,
                'is_active' => true,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
        ], ['slug'], [
            'name',
            'price',
            'max_appointments_per_month',
            'mp_plan_id',
            'is_active',
            'updated_at',
        ]);
    }
}
