<?php

namespace App\Http\Requests;

use App\Services\Helpers\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRequest extends FormRequest
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
     * @return array
     */
    public function rules(): array
    {
        return match (true) {
            $this->routeIs('user.login') => $this->loginRules(),
            $this->routeIs('user.register'),
            $this->routeIs('user.edit-user') => $this->editOrRegisterRules(),
            $this->routeIs('user.reset-password-token') => $this->resetPasswordTokenRules(),
            $this->routeIs('user.forgot-password') => $this->forgotPasswordRules(),
            default => []
        };
    }

    protected function loginRules(): array
    {
        return [
            'email' => ['required', 'string', 'email:rfc', 'max:255'],
            'password' => ['required', 'string', 'min:8']
        ];
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

    protected function resetPasswordTokenRules(): array
    {
        return [
            'token' => ['required'],
            'email' => ['required'],
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

    /**
     * @throws HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            ApiResponse::failed(
                $validator->errors()->first(),
                $validator->errors()->toArray(),
                httpStatusCode: 422
            )
        );
    }
}
