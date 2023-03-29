<?php

namespace App\Http\Requests;

use App\Services\Helpers\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AdminRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return match (true) {
            $this->routeIs('admin.login'), => $this->loginRules(),
            $this->routeIs('admin.create'),
            $this->routeIs('admin.edit-user') => $this->createAdminRules(),
            default => []
        };
    }

    protected function createAdminRules(): array
    {
        return [
            'first_name' => ['required'],
            'last_name' => ['required'],
            'email' => ['required'],
            'password' => ['required'],
            'password_confirmation' => ['required'],
            'avatar' => ['required'],
            'address' => ['required'],
            'phone_number' => ['required'],
            'is_marketing' => ['required'],
        ];
    }

    protected function loginRules(): array
    {
        return [
            'email' => ['required'],
            'password' => ['required'],
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
