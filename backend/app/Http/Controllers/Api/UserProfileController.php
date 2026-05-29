<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    public function index(User $user): JsonResponse
    {
        return response()->json([
            'data' => $user->profiles()->orderBy('profiles.id')->get(),
        ]);
    }

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

    public function detach(User $user, Profile $profile): JsonResponse
    {
        $user->profiles()->detach($profile->id);

        return response()->json([
            'message' => 'Perfil desassociado do usuário com sucesso.',
            'data' => $user->load('profiles'),
        ]);
    }
}
