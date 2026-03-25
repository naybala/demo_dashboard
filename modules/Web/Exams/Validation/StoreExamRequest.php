<?php

namespace BasicDashboard\Web\Exams\Validation;

use Illuminate\Foundation\Http\FormRequest;

class StoreExamRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'                => 'required|string|max:255',
            'academic_session_id' => 'required|exists:academic_sessions,id',
            'term'                => 'required|string|max:50',
            'status'              => 'nullable|string',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->academic_session_id) {
            $this->merge([
                'academic_session_id' => customDecoder($this->academic_session_id),
            ]);
        }
    }
}
