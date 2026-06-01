<?php

namespace Tests\Feature;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Validates profile management API behavior.
 *
 * Covers administrator-only profile listing, creation, validation, update,
 * soft delete and listing profiles with soft-deleted records.
 */
class ProfileManagementTest extends TestCase
{
    use RefreshDatabase;

    /**
    * Creates an authenticated administrator user for protected profile endpoints.
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

    public function test_administrator_can_list_profiles(): void
    {
        $admin = $this->createAdministratorUser();

        Profile::create([
            'name' => 'Operador',
        ]);

        $token = $admin->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/profiles');

        $response
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'created_at',
                        'updated_at',
                        'deleted_at',
                    ],
                ],
            ])
            ->assertJsonFragment([
                'name' => 'Administrador',
            ])
            ->assertJsonFragment([
                'name' => 'Operador',
            ]);
    }

    public function test_administrator_can_create_profile(): void
    {
        $admin = $this->createAdministratorUser();

        $token = $admin->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/profiles', [
                'name' => 'Operador',
            ]);

        $response
            ->assertCreated()
            ->assertJson([
                'message' => 'Perfil criado com sucesso.',
            ]);

        $this->assertDatabaseHas('profiles', [
            'name' => 'Operador',
        ]);
    }

    public function test_profile_creation_requires_name(): void
    {
        $admin = $this->createAdministratorUser();

        $token = $admin->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/profiles', []);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'name',
            ]);
    }

    public function test_administrator_can_update_profile(): void
    {
        $admin = $this->createAdministratorUser();

        $profile = Profile::create([
            'name' => 'Operador',
        ]);

        $token = $admin->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->putJson("/api/profiles/{$profile->id}", [
                'name' => 'Operador Atualizado',
            ]);

        $response
            ->assertOk()
            ->assertJson([
                'message' => 'Perfil atualizado com sucesso.',
            ]);

        $this->assertDatabaseHas('profiles', [
            'id' => $profile->id,
            'name' => 'Operador Atualizado',
        ]);
    }

    public function test_administrator_can_soft_delete_profile(): void
    {
        $admin = $this->createAdministratorUser();

        $profile = Profile::create([
            'name' => 'Operador',
        ]);

        $token = $admin->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->deleteJson("/api/profiles/{$profile->id}");

        $response
            ->assertOk()
            ->assertJson([
                'message' => 'Perfil excluído com sucesso.',
            ]);

        $this->assertSoftDeleted('profiles', [
            'id' => $profile->id,
        ]);
    }

    public function test_profile_listing_can_include_soft_deleted_profiles(): void
    {
        $admin = $this->createAdministratorUser();

        $deletedProfile = Profile::create([
            'name' => 'Operador Excluído',
        ]);

        $deletedProfile->delete();

        $token = $admin->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/profiles?with_trashed=true');

        $response
            ->assertOk()
            ->assertJsonFragment([
                'name' => 'Operador Excluído',
            ]);
    }

    public function test_common_user_cannot_create_profile(): void
    {
        $commonUser = User::factory()->create([
            'email' => 'common@example.com',
            'password' => 'password',
        ]);

        $token = $commonUser->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/profiles', [
                'name' => 'Operador',
            ]);

        $response
            ->assertForbidden()
            ->assertJson([
                'message' => 'Acesso permitido apenas para administradores.',
            ]);
    }
}
