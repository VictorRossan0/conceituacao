<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Handles user-profile association operations.
 *
 * This controller manages the many-to-many relationship between users and
 * profiles. These routes are restricted to administrator users.
 */
class UserProfileController extends Controller
{
    /**
     * Lists all active profiles assigned to a user.
     */
    public function index(User $user): JsonResponse
    {
        return response()->json([
            'data' => $user->profiles()->orderBy('profiles.id')->get(),
        ]);
    }

    /**
     * Associates an active profile with a user.
     *
     * Soft-deleted profiles are ignored because the Profile query respects
     * Laravel SoftDeletes by default.
     */
    public function attach(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate([
            'profile_id' => ['required', 'integer'],
        ]);

        $profile = Profile::whereKey($validated['profile_id'])->first();

        if (! $profile) {
            return response()->json([
                'message' => 'Perfil não encontrado ou excluído.',
            ], 404);
        }

        $user->profiles()->syncWithoutDetaching([
            $profile->id,
        ]);

        return response()->json([
            'message' => 'Perfil associado ao usuário com sucesso.',
            'data' => $user->load('profiles'),
        ]);
    }

    /**
     * Removes the association between a user and a profile.
     *
     * The profile itself is not deleted. Only the pivot table relationship is
     * removed.
     */
    public function detach(User $user, Profile $profile): JsonResponse
    {
        $user->profiles()->detach($profile->id);

        return response()->json([
            'message' => 'Perfil desassociado do usuário com sucesso.',
            'data' => $user->load('profiles'),
        ]);
    }
}
