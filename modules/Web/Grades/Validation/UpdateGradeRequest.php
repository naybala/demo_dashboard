<?php

namespace BasicDashboard\Web\Grades\Validation;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGradeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $decodedId = customDecoder($this->route('grade'));
        return [
            'name'   => 'required|string|max:255',
            'code'   => 'required|string|max:50|unique:grades,code,' . $decodedId,
            'status' => 'nullable|string',
        ];
    }
}
