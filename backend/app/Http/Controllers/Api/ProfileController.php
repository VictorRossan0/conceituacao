<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProfileRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\Profile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Handles profile management API operations.
 *
 * Profile management is restricted to authenticated administrator users
 * through the EnsureUserIsAdministrator middleware.
 */
class ProfileController extends Controller
{
    /**
     * Lists profiles with optional soft delete filters.
     *
     * Supported query parameters:
     * - with_trashed=true: returns active and soft-deleted profiles.
     * - only_trashed=true: returns only soft-deleted profiles.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Profile::orderBy('id');

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
     * Creates a new access profile.
     */
    public function store(StoreProfileRequest $request): JsonResponse
    {
        $profile = Profile::create($request->validated());

        return response()->json([
            'message' => 'Perfil criado com sucesso.',
            'data' => $profile,
        ], 201);
    }

    /**
     * Displays a specific active profile.
     */
    public function show(Profile $profile): JsonResponse
    {
        return response()->json([
            'data' => $profile,
        ]);
    }

    /**
     * Updates an existing profile.
     */
    public function update(UpdateProfileRequest $request, Profile $profile): JsonResponse
    {
        $profile->update($request->validated());

        return response()->json([
            'message' => 'Perfil atualizado com sucesso.',
            'data' => $profile,
        ]);
    }

    /**
     * Soft deletes a profile.
     *
     * The record is preserved in the database with deleted_at filled because
     * the Profile model uses Laravel SoftDeletes.
     */
    public function destroy(Profile $profile): JsonResponse
    {
        $profile->delete();

        return response()->json([
            'message' => 'Perfil excluído com sucesso.',
        ]);
    }
}
