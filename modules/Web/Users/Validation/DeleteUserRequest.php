<?php

namespace BasicDashboard\Web\Users\Validation;

use Illuminate\Foundation\Http\FormRequest;

class DeleteUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "id" => "required",
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => 'User ID is required',
        ];
    }
}
