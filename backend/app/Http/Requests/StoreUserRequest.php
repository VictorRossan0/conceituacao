<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validates data required to create a user.
 *
 * This request centralizes user creation rules, ensuring that name, email
 * and password are provided before the controller persists a new user.
 */
class StoreUserRequest extends FormRequest
{
    /**
     * Allows authenticated requests to use this validation class.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Returns validation rules for creating users.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
        ];
    }
}
