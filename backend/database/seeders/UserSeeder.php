<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Seeds the default administrator user.
 *
 * This user is created to simplify local testing and API validation through
 * Postman or the frontend application.
 */
class UserSeeder extends Seeder
{
    /**
     * Creates a default administrator user and assigns the Administrador profile.
     *
     * Default credentials:
     * - email: admin@example.com
     * - password: password
     */
    public function run(): void
    {
        $adminProfile = Profile::firstOrCreate([
            'name' => 'Administrador',
        ]);

        $adminUser = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('password'),
            ]
        );

        $adminUser->profiles()->syncWithoutDetaching([
            $adminProfile->id,
        ]);
    }
}
