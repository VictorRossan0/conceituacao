<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
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

    public function messages(): array
    {
        return [
            'name.required' => 'O campo perfil é obrigatório.',
            'name.unique' => 'Este perfil já está cadastrado.',
        ];
    }
}
