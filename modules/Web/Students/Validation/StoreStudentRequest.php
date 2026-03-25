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
        $studentId = $this->route('student') ? customDecoder($this->route('student')) : null;

        return [
            'student_code'      => 'required|string|max:50|unique:students,student_code,' . $studentId,
            'academic_year'     => 'nullable|string|max:20',
            'class_id'          => 'nullable|exists:classes,id',
            'full_name'         => 'required|string|max:255',
            'first_name'        => 'nullable|string|max:255',
            'last_name'         => 'nullable|string|max:255',
            'other_name'        => 'nullable|string|max:255',
            'email'             => 'nullable|email|max:255',
            'gender'            => 'nullable|string|max:20',
            'dob'               => 'nullable|date',
            'nrc'               => 'nullable|string|max:100',
            'place_of_birth'    => 'nullable|string|max:255',
            'nationality'       => 'nullable|string|max:100',
            'religion'          => 'nullable|string|max:100',
            'address'           => 'nullable|string',
            'registration_date' => 'nullable|date',
            
            // Academic Details
            'school_attended'   => 'nullable|string|max:255',
            'grade_attended'    => 'nullable|string|max:100',
            'year_attended'     => 'nullable|string|max:50',
            'grade_id'          => 'nullable|exists:grades,id',

            // Father's Information
            'father_name'          => 'nullable|string|max:255',
            'father_nrc'           => 'nullable|string|max:100',
            'father_qualification' => 'nullable|string|max:255',
            'father_job'           => 'nullable|string|max:255',
            'father_phone'         => 'nullable|string|max:50',
            'father_email'         => 'nullable|email|max:255',
            'father_address'       => 'nullable|string',
            'father_alive_status'  => 'nullable|string|max:50',

            // Mother's Information
            'mother_name'          => 'nullable|string|max:255',
            'mother_nrc'           => 'nullable|string|max:100',
            'mother_qualification' => 'nullable|string|max:255',
            'mother_job'           => 'nullable|string|max:255',
            'mother_phone'         => 'nullable|string|max:50',
            'mother_email'         => 'nullable|email|max:255',
            'mother_address'       => 'nullable|string',
            'mother_alive_status'  => 'nullable|string|max:50',

            // Guardian's Information
            'guardian_name'          => 'nullable|string|max:255',
            'guardian_nrc'           => 'nullable|string|max:100',
            'guardian_qualification' => 'nullable|string|max:255',
            'guardian_job'           => 'nullable|string|max:255',
            'guardian_phone'         => 'nullable|string|max:50',
            'guardian_email'         => 'nullable|email|max:255',
            'guardian_address'       => 'nullable|string',
            'guardian_alive_status'  => 'nullable|string|max:50',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->class_id) {
            $this->merge(['class_id' => customDecoder($this->class_id)]);
        }
        if ($this->grade_id) {
            $this->merge(['grade_id' => customDecoder($this->grade_id)]);
        }
    }
}
