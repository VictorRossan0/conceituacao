<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProfileRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\Profile;
use Illuminate\Http\JsonResponse;

class ProfileController extends Controller
{
    public function index(): JsonResponse
    {
        $profiles = Profile::orderBy('name')->get();

        return response()->json([
            'data' => $profiles,
        ]);
    }

    public function store(StoreProfileRequest $request): JsonResponse
    {
        $profile = Profile::create($request->validated());

        return response()->json([
            'message' => 'Perfil criado com sucesso.',
            'data' => $profile,
        ], 201);
    }

    public function show(Profile $profile): JsonResponse
    {
        return response()->json([
            'data' => $profile,
        ]);
    }

    public function update(UpdateProfileRequest $request, Profile $profile): JsonResponse
    {
        $profile->update($request->validated());

        return response()->json([
            'message' => 'Perfil atualizado com sucesso.',
            'data' => $profile,
        ]);
    }

    public function destroy(Profile $profile): JsonResponse
    {
        $profile->delete();

        return response()->json([
            'message' => 'Perfil excluído com sucesso.',
        ]);
    }
}
