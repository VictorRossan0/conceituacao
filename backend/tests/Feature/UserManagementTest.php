<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Validates user management API behavior.
 *
 * Covers user listing, creation, validation, update, soft delete and listing
 * users with soft-deleted records.
 */
class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_list_users(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        User::factory()->create([
            'email' => 'user@example.com',
            'password' => 'password',
        ]);

        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/users');

        $response
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'email',
                        'created_at',
                        'updated_at',
                        'deleted_at',
                        'profiles',
                    ],
                ],
            ]);
    }

    public function test_authenticated_user_can_create_user(): void
    {
        $authUser = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        $token = $authUser->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/users', [
                'name' => 'Novo Usuário',
                'email' => 'novo.usuario@example.com',
                'password' => 'password',
            ]);

        $response
            ->assertCreated()
            ->assertJson([
                'message' => 'Usuário criado com sucesso.',
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'novo.usuario@example.com',
        ]);
    }

    public function test_user_creation_requires_name_and_email(): void
    {
        $authUser = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        $token = $authUser->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/users', [
                'password' => 'password',
            ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'name',
                'email',
            ]);
    }

    public function test_authenticated_user_can_update_user(): void
    {
        $authUser = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        $userToUpdate = User::factory()->create([
            'email' => 'old.email@example.com',
            'password' => 'password',
        ]);

        $token = $authUser->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->putJson("/api/users/{$userToUpdate->id}", [
                'name' => 'Usuário Atualizado',
                'email' => 'updated.email@example.com',
            ]);

        $response
            ->assertOk()
            ->assertJson([
                'message' => 'Usuário atualizado com sucesso.',
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $userToUpdate->id,
            'name' => 'Usuário Atualizado',
            'email' => 'updated.email@example.com',
        ]);
    }

    public function test_authenticated_user_can_soft_delete_user(): void
    {
        $authUser = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        $userToDelete = User::factory()->create([
            'email' => 'delete.me@example.com',
            'password' => 'password',
        ]);

        $token = $authUser->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->deleteJson("/api/users/{$userToDelete->id}");

        $response
            ->assertOk()
            ->assertJson([
                'message' => 'Usuário excluído com sucesso.',
            ]);

        $this->assertSoftDeleted('users', [
            'id' => $userToDelete->id,
        ]);
    }

    public function test_user_listing_can_include_soft_deleted_users(): void
    {
        $authUser = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        $deletedUser = User::factory()->create([
            'email' => 'deleted.user@example.com',
            'password' => 'password',
        ]);

        $deletedUser->delete();

        $token = $authUser->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/users?with_trashed=true');

        $response
            ->assertOk()
            ->assertJsonFragment([
                'email' => 'deleted.user@example.com',
            ]);
    }
}
