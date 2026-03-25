<?php

namespace BasicDashboard\Web\Students\Validation;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_code'      => 'required|string|max:50|unique:students,student_code',
            'first_name'        => 'required|string|max:255',
            'last_name'         => 'required|string|max:255',
            'email'             => 'nullable|email|max:255',
            'gender'            => 'nullable|string',
            'dob'               => 'nullable|date',
            'registration_date' => 'nullable|date',
        ];
    }
}
