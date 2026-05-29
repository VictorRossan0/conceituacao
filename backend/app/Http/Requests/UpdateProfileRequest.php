<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validates data required to update a profile.
 *
 * This request keeps profile update validation centralized and preserves
 * profile name uniqueness while ignoring the current profile record.
 */
class UpdateProfileRequest extends FormRequest
{
    /**
     * Allows authenticated administrator requests to use this validation class.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Returns validation rules for updating profiles.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $profileId = $this->route('profile')?->id;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('profiles', 'name')->ignore($profileId),
            ],
        ];
    }

    /**
     * Returns custom validation messages for profile updates.
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
