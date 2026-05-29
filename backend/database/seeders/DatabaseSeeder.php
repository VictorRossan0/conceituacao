<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Main database seeder.
 *
 * This class orchestrates the initial database seed process required for
 * local development and test validation.
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Runs the application seeders in the required order.
     *
     * Profiles are seeded before users because the default administrator user
     * must be associated with the Administrador profile.
     */
    public function run(): void
    {
        $this->call([
            ProfileSeeder::class,
            UserSeeder::class,
        ]);
    }
}
