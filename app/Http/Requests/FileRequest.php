<?php

namespace App\Http\Requests;

use App\Services\Concerns\Auth\ValidationError;
use Illuminate\Foundation\Http\FormRequest;

class FileRequest extends FormRequest
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
            'file' => ['required', 'file'],
        ];
    }
}
