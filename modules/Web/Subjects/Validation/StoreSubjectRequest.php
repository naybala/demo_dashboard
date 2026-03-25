<?php

namespace BasicDashboard\Web\Subjects\Validation;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $subjectId = $this->route('subject') ? customDecoder($this->route('subject')) : null;
        return [
            'name'   => 'required|string|max:255',
            'code'   => 'required|string|max:50|unique:subjects,code,' . $subjectId,
            'type'   => 'required|string|in:theory,practical,both',
            'status' => 'nullable|string',
        ];
    }
}
