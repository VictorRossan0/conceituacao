<?php

namespace Tests\Feature;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Validates user-profile association API behavior.
 *
 * Covers listing assigned profiles, attaching profiles, preventing duplicate
 * associations, detaching profiles and blocking soft-deleted profiles.
 */
class UserProfileAssociationTest extends TestCase
{
    use RefreshDatabase;

    /**
    * Creates an authenticated administrator user for association endpoints.
    */
    private function createAdministratorUser(): User
    {
        $adminProfile = Profile::create([
            'name' => 'Administrador',
        ]);

        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        $admin->profiles()->attach($adminProfile->id);

        return $admin;
    }

    public function test_administrator_can_list_user_profiles(): void
    {
        $admin = $this->createAdministratorUser();

        $operatorProfile = Profile::create([
            'name' => 'Operador',
        ]);

        $targetUser = User::factory()->create([
            'email' => 'target@example.com',
            'password' => 'password',
        ]);

        $targetUser->profiles()->attach($operatorProfile->id);

        $token = $admin->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson("/api/users/{$targetUser->id}/profiles");

        $response
            ->assertOk()
            ->assertJsonFragment([
                'name' => 'Operador',
            ]);
    }

    public function test_administrator_can_attach_profile_to_user(): void
    {
        $admin = $this->createAdministratorUser();

        $targetUser = User::factory()->create([
            'email' => 'target@example.com',
            'password' => 'password',
        ]);

        $profile = Profile::create([
            'name' => 'Operador',
        ]);

        $token = $admin->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson("/api/users/{$targetUser->id}/profiles", [
                'profile_id' => $profile->id,
            ]);

        $response
            ->assertOk()
            ->assertJson([
                'message' => 'Perfil associado ao usuário com sucesso.',
            ]);

        $this->assertDatabaseHas('profile_user', [
            'user_id' => $targetUser->id,
            'profile_id' => $profile->id,
        ]);
    }

    public function test_attaching_same_profile_twice_does_not_duplicate_association(): void
    {
        $admin = $this->createAdministratorUser();

        $targetUser = User::factory()->create([
            'email' => 'target@example.com',
            'password' => 'password',
        ]);

        $profile = Profile::create([
            'name' => 'Operador',
        ]);

        $token = $admin->createToken('test-token')->plainTextToken;

        $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson("/api/users/{$targetUser->id}/profiles", [
                'profile_id' => $profile->id,
            ])
            ->assertOk();

        $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson("/api/users/{$targetUser->id}/profiles", [
                'profile_id' => $profile->id,
            ])
            ->assertOk();

        $this->assertDatabaseCount('profile_user', 2);

        $this->assertDatabaseHas('profile_user', [
            'user_id' => $targetUser->id,
            'profile_id' => $profile->id,
        ]);
    }

    public function test_administrator_can_detach_profile_from_user(): void
    {
        $admin = $this->createAdministratorUser();

        $targetUser = User::factory()->create([
            'email' => 'target@example.com',
            'password' => 'password',
        ]);

        $profile = Profile::create([
            'name' => 'Operador',
        ]);

        $targetUser->profiles()->attach($profile->id);

        $token = $admin->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->deleteJson("/api/users/{$targetUser->id}/profiles/{$profile->id}");

        $response
            ->assertOk()
            ->assertJson([
                'message' => 'Perfil desassociado do usuário com sucesso.',
            ]);

        $this->assertDatabaseMissing('profile_user', [
            'user_id' => $targetUser->id,
            'profile_id' => $profile->id,
        ]);
    }

    public function test_cannot_attach_soft_deleted_profile_to_user(): void
    {
        $admin = $this->createAdministratorUser();

        $targetUser = User::factory()->create([
            'email' => 'target@example.com',
            'password' => 'password',
        ]);

        $deletedProfile = Profile::create([
            'name' => 'Perfil Excluído',
        ]);

        $deletedProfile->delete();

        $token = $admin->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson("/api/users/{$targetUser->id}/profiles", [
                'profile_id' => $deletedProfile->id,
            ]);

        $response
            ->assertNotFound()
            ->assertJson([
                'message' => 'Perfil não encontrado ou excluído.',
            ]);

        $this->assertDatabaseMissing('profile_user', [
            'user_id' => $targetUser->id,
            'profile_id' => $deletedProfile->id,
        ]);
    }

    public function test_common_user_cannot_detach_profile_from_user(): void
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

        $targetUser->profiles()->attach($profile->id);

        $token = $commonUser->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->deleteJson("/api/users/{$targetUser->id}/profiles/{$profile->id}");

        $response
            ->assertForbidden()
            ->assertJson([
                'message' => 'Acesso permitido apenas para administradores.',
            ]);

        $this->assertDatabaseHas('profile_user', [
            'user_id' => $targetUser->id,
            'profile_id' => $profile->id,
        ]);
    }
}
