<?php

namespace App\Http\Requests;

use App\Services\Concerns\Auth\ValidationError;
use Illuminate\Foundation\Http\FormRequest;

class AdminRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return match (true) {
            $this->routeIs('admin.edit-user') => $this->updateUserDetailsRules(),
            default => []
        };
    }

    protected function updateUserDetailsRules(): array
    {
        return [
            'first_name' => ['nullable'],
            'last_name' => ['nullable'],
            'email' => ['nullable', 'string', 'email:rfc', 'unique:users', 'max:255'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required'],
            'avatar' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
            'phone_number' => ['nullable', 'unique:users'],
            'is_marketing' => ['nullable', 'bool'],
        ];
    }

    /**
     * Prepare inputs for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_marketing' => $this->toBoolean(request()->is_marketing),
        ]);
    }

    /**
     * Convert to boolean
     *
     * @param mixed $boolean
     * @return bool|null
     */
    private function toBoolean(mixed $boolean): bool|null
    {
        return filter_var($boolean, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    }
}
