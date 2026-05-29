<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * Handles user management API operations.
 *
 * This controller exposes CRUD operations for users and supports optional
 * soft delete filters in the listing endpoint.
 */
class UserController extends Controller
{
    /**
     * Lists users with optional soft delete filters.
     *
     * Supported query parameters:
     * - with_trashed=true: returns active and soft-deleted users.
     * - only_trashed=true: returns only soft-deleted users.
     */
    public function index(Request $request): JsonResponse
    {
        $query = User::with('profiles')->orderBy('id');

        if ($request->boolean('with_trashed')) {
            $query->withTrashed();
        }

        if ($request->boolean('only_trashed')) {
            $query->onlyTrashed();
        }

        return response()->json([
            'data' => $query->get(),
        ]);
    }

    /**
     * Creates a new user.
     *
     * The password is hashed before persistence and the created user is
     * returned with its profile relationships loaded.
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json([
            'message' => 'Usuário criado com sucesso.',
            'data' => $user->load('profiles'),
        ], 201);
    }

    /**
     * Displays a specific active user with assigned profiles.
     */
    public function show(User $user): JsonResponse
    {
        return response()->json([
            'data' => $user->load('profiles'),
        ]);
    }

    /**
     * Updates an existing user.
     *
     * Password update is optional. When omitted, the current password is kept.
     */
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $validated = $request->validated();

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return response()->json([
            'message' => 'Usuário atualizado com sucesso.',
            'data' => $user->load('profiles'),
        ]);
    }

    /**
     * Soft deletes a user.
     *
     * The record is not physically removed from the database because the User
     * model uses Laravel SoftDeletes.
     */
    public function destroy(User $user): JsonResponse
    {
        $user->delete();

        return response()->json([
            'message' => 'Usuário excluído com sucesso.',
        ]);
    }
}
