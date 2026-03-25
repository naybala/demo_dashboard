<?php

namespace BasicDashboard\Web\Marks\Validation;

use Illuminate\Foundation\Http\FormRequest;

class StoreMarkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_id'     => 'required|exists:students,id',
            'exam_id'        => 'required|exists:exams,id',
            'subject_id'     => 'required|exists:subjects,id',
            'marks_obtained' => 'required|numeric|min:0',
            'grade'          => 'nullable|string|max:5',
            'status'         => 'nullable|string',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->student_id) {
            $this->merge([
                'student_id' => customDecoder($this->student_id),
            ]);
        }
        if ($this->exam_id) {
            $this->merge([
                'exam_id' => customDecoder($this->exam_id),
            ]);
        }
        if ($this->subject_id) {
            $this->merge([
                'subject_id' => customDecoder($this->subject_id),
            ]);
        }
    }
}
