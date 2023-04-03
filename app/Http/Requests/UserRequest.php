<?php

namespace App\Http\Requests;

use App\Services\Concerns\Auth\ValidationError;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        return match (true) {
            $this->routeIs('user.edit-user') => $this->editOrRegisterRules(),
            default => []
        };
    }

    protected function editOrRegisterRules(): array
    {
        return [
            'first_name' => ['required'],
            'last_name' => ['required'],
            'email' => ['required', 'string', 'email:rfc', 'unique:users', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required'],
            'avatar' => ['nullable', 'string'],
            'address' => ['required', 'string'],
            'phone_number' => ['required', 'unique:users'],
            'is_marketing' => ['nullable', 'bool'],
        ];
    }
}
