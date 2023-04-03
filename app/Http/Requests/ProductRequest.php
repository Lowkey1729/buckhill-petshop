<?php

namespace App\Http\Requests;

use App\Services\Concerns\Auth\ValidationError;
use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            $this->routeIs('product.create-product') => $this->createProductRules(),
            $this->routeIs('product.update-product') => $this->updateProductRules(),
            default => []
        };
    }

    protected function createProductRules(): array
    {
        return [
            'category_uuid' => ['required', 'uuid', 'exists:categories,uuid'],
            'title' => ['required', 'string'],
            'price' => ['required', 'numeric', 'gt:0'],
            'description' => ['required', 'string'],
            'brand' => ['required', 'string'],
            'image' => ['required', 'uuid'],
        ];
    }

    protected function updateProductRules(): array
    {
        return [
            'category_uuid' => ['nullable', 'uuid', 'exists:categories,uuid'],
            'title' => ['nullable', 'string'],
            'price' => ['nullable', 'numeric', 'gt:0'],
            'description' => ['nullable', 'string'],
            'brand' => ['nullable', 'string'],
            'image' => ['nullable', 'uuid'],

        ];
    }
}
