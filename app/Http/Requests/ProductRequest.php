<?php

namespace App\Http\Requests;

use App\Services\Helpers\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProductRequest extends FormRequest
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
