<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use LogicException;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = config('odontolead.superadmin.password');

        if (! is_string($password) || $password === '') {
            if (! App::environment(['local', 'testing'])) {
                throw new LogicException('SUPERADMIN_PASSWORD must be configured outside local and testing environments.');
            }

            $password = 'password';
        }

        $superAdmin = User::query()->firstOrNew([
            'email' => (string) config('odontolead.superadmin.email'),
        ]);

        $superAdmin->forceFill([
            'clinic_id' => null,
            'name' => (string) config('odontolead.superadmin.name'),
            'password' => $password,
            'is_superadmin' => true,
            'email_verified_at' => now(),
        ])->save();
    }
}
