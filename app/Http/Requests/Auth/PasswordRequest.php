<?php

namespace App\Http\Requests\Auth;

use App\Services\Traits\Auth\ValidationError;
use Illuminate\Foundation\Http\FormRequest;

class PasswordRequest extends FormRequest
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
            $this->routeIs('user.reset-password-token') => $this->resetPasswordTokenRules(),
            $this->routeIs('user.forgot-password') => $this->forgotPasswordRules(),
            default => []
        };
    }

    protected function resetPasswordTokenRules(): array
    {
        return [
            'token' => ['required'],
            'email' => ['required', 'string', 'email:rfc', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required'],
        ];
    }

    protected function forgotPasswordRules(): array
    {
        return [
            'email' => ['required', 'string', 'email:rfc', 'max:255'],
        ];
    }
}
