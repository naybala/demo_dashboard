<?php

namespace BasicDashboard\Web\Classes\Validation;

use Illuminate\Foundation\Http\FormRequest;

class StoreClassRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'     => 'required|string|max:255',
            'grade_id' => 'required|exists:grades,id',
            'status'   => 'nullable|string',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->grade_id) {
            $this->merge([
                'grade_id' => customDecoder($this->grade_id),
            ]);
        }
    }
}
