<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validates data required to update a user.
 *
 * This request keeps user update validation outside the controller and
 * preserves email uniqueness while ignoring the current user record.
 */
class UpdateUserRequest extends FormRequest
{
    /**
     * Allows authenticated requests to use this validation class.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Returns validation rules for updating users.
     *
     * Password is optional during updates. When it is not provided, the
     * current password remains unchanged.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $userId = $this->route('user')?->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'password' => ['nullable', 'string', 'min:6'],
        ];
    }
}
