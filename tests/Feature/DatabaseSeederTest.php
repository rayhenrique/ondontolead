<?php

namespace Tests\Feature;

use Database\Seeders\SuperAdminSeeder;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use LogicException;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_creates_idempotent_initial_data(): void
    {
        $this->travelTo('2026-09-15 12:00:00');
        config()->set('odontolead.superadmin', [
            'name' => 'System Administrator',
            'email' => 'admin@example.com',
            'password' => 'secure-test-password',
        ]);

        $this->seed();
        $this->seed();

        $this->assertDatabaseCount('plans', 2);
        $this->assertDatabaseHas('plans', [
            'slug' => 'essencial',
            'max_appointments_per_month' => 100,
            'is_active' => true,
        ]);
        $this->assertDatabaseHas('plans', [
            'slug' => 'profissional',
            'max_appointments_per_month' => 300,
            'is_active' => true,
        ]);

        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseHas('users', [
            'clinic_id' => null,
            'name' => 'System Administrator',
            'email' => 'admin@example.com',
            'is_superadmin' => true,
        ]);
        $passwordHash = DB::table('users')->where('email', 'admin@example.com')->value('password');
        $this->assertIsString($passwordHash);
        $this->assertTrue(Hash::check('secure-test-password', $passwordHash));

        $this->assertDatabaseCount('app_releases', 8);
        $this->assertDatabaseHas('app_releases', [
            'version' => 'v1.0.0',
            'show_modal' => true,
        ]);
    }

    public function test_rejects_missing_superadmin_password_in_production(): void
    {
        app()->detectEnvironment(fn (): string => 'production');
        config()->set('odontolead.superadmin.password');

        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('SUPERADMIN_PASSWORD must be configured');

        app(SuperAdminSeeder::class)->run();
    }
}
