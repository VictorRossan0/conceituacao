<?php

namespace Database\Seeders;

use App\Models\Profile;
use Illuminate\Database\Seeder;

/**
 * Seeds the default administrator profile.
 *
 * The Administrador profile is required by the access control layer to
 * identify users allowed to manage profiles and user-profile associations.
 */
class ProfileSeeder extends Seeder
{
    /**
     * Creates the Administrador profile when it does not already exist.
     */
    public function run(): void
    {
        Profile::firstOrCreate([
            'name' => 'Administrador',
        ]);
    }
}
