<?php

namespace App\Services\Concerns\Auth;

use App\Services\Helpers\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

trait ValidationError
{
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
