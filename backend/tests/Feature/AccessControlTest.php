<?php

namespace Tests\Feature;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Validates administrator access control rules.
 *
 * Ensures that profile management and user-profile association endpoints are
 * restricted to users with the Administrador profile.
 */
class AccessControlTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_profile_management(): void
    {
        $response = $this->getJson('/api/profiles');

        $response->assertUnauthorized();
    }

    public function test_common_user_cannot_access_profile_management(): void
    {
        $user = User::factory()->create([
            'email' => 'common@example.com',
            'password' => 'password',
        ]);

        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/profiles');

        $response
            ->assertForbidden()
            ->assertJson([
                'message' => 'Acesso permitido apenas para administradores.',
            ]);
    }

    public function test_administrator_can_access_profile_management(): void
    {
        $adminProfile = Profile::create([
            'name' => 'Administrador',
        ]);

        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        $admin->profiles()->attach($adminProfile->id);

        $token = $admin->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/profiles');

        $response->assertOk();
    }

    public function test_common_user_cannot_associate_profiles_to_users(): void
    {
        $commonUser = User::factory()->create([
            'email' => 'common@example.com',
            'password' => 'password',
        ]);

        $targetUser = User::factory()->create([
            'email' => 'target@example.com',
            'password' => 'password',
        ]);

        $profile = Profile::create([
            'name' => 'Operador',
        ]);

        $token = $commonUser->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson("/api/users/{$targetUser->id}/profiles", [
                'profile_id' => $profile->id,
            ]);

        $response->assertForbidden();
    }

    public function test_administrator_can_associate_profiles_to_users(): void
    {
        $adminProfile = Profile::create([
            'name' => 'Administrador',
        ]);

        $operatorProfile = Profile::create([
            'name' => 'Operador',
        ]);

        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        $targetUser = User::factory()->create([
            'email' => 'target@example.com',
            'password' => 'password',
        ]);

        $admin->profiles()->attach($adminProfile->id);

        $token = $admin->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson("/api/users/{$targetUser->id}/profiles", [
                'profile_id' => $operatorProfile->id,
            ]);

        $response
            ->assertOk()
            ->assertJson([
                'message' => 'Perfil associado ao usuário com sucesso.',
            ]);

        $this->assertDatabaseHas('profile_user', [
            'user_id' => $targetUser->id,
            'profile_id' => $operatorProfile->id,
        ]);
    }
}
