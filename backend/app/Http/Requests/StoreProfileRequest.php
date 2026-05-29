<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validates data required to create a profile.
 *
 * This request ensures that every profile has a required and unique name,
 * which is used as the system access profile label.
 */
class StoreProfileRequest extends FormRequest
{
    /**
     * Allows authenticated administrator requests to use this validation class.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Returns validation rules for creating profiles.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:profiles,name'],
        ];
    }

    /**
     * Returns custom validation messages for profile creation.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'O campo perfil é obrigatório.',
            'name.unique' => 'Este perfil já está cadastrado.',
        ];
    }
}
