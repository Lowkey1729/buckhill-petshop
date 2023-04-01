<?php

namespace App\Http\Requests\Auth;

use App\Services\Traits\Auth\ValidationError;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    use ValidationError;
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
     * @return array
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required'],
            'last_name' => ['required'],
            'email' => ['required', 'string', 'email:rfc', 'unique:users', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required'],
            'avatar' => ['required', 'string'],
            'address' => ['required', 'string'],
            'phone_number' => ['required', 'unique:users'],
            'is_marketing' => ['nullable', 'bool'],
        ];
    }
}
