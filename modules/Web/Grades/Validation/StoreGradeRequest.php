<?php

namespace BasicDashboard\Web\Grades\Validation;

use Illuminate\Foundation\Http\FormRequest;

class StoreGradeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'   => 'required|string|max:255',
            'code'   => 'required|string|max:50|unique:grades,code',
            'status' => 'nullable|string',
        ];
    }
}
