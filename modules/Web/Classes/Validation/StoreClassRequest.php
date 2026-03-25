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
            'grade_id'                => 'required|integer',
            'session_id'              => 'nullable|integer',
            'section'                 => 'required|string|max:100',
            'name'                    => 'nullable|string|max:255',
            'capacity'                => 'nullable|integer|min:1',
            'teaching_days'           => 'nullable|string|max:255',
            'start_time'              => 'nullable',
            'end_time'                => 'nullable',
            'attendance_mode'         => 'nullable|string|max:100',
            'allow_makeup_attendance' => 'nullable|string|max:50',
            'head_teacher_id'         => 'nullable|integer',
            'co_teacher_id'           => 'nullable|integer',
            'notes'                   => 'nullable|string',
            'status'                  => 'nullable|string',
            'subjects'                => 'nullable|array',
            'subjects.*.subject_id'   => 'required|integer',
            'subjects.*.teacher_id'   => 'nullable|integer',
            'subjects.*.hours_per_week'=> 'nullable|integer|min:1',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->grade_id && is_string($this->grade_id)) {
            $this->merge(['grade_id' => customDecoder($this->grade_id)]);
        }
        if ($this->session_id && is_string($this->session_id)) {
            $this->merge(['session_id' => customDecoder($this->session_id)]);
        }
        if ($this->head_teacher_id && is_string($this->head_teacher_id)) {
            $this->merge(['head_teacher_id' => customDecoder($this->head_teacher_id)]);
        }
        if ($this->co_teacher_id && is_string($this->co_teacher_id)) {
            $this->merge(['co_teacher_id' => customDecoder($this->co_teacher_id)]);
        }

        if ($this->subjects && is_array($this->subjects)) {
            $subjects = $this->subjects;
            foreach ($subjects as $key => $subject) {
                if (isset($subject['subject_id']) && is_string($subject['subject_id'])) {
                    $subjects[$key]['subject_id'] = customDecoder($subject['subject_id']);
                }
                if (isset($subject['teacher_id']) && is_string($subject['teacher_id'])) {
                    $subjects[$key]['teacher_id'] = customDecoder($subject['teacher_id']);
                }
            }
            $this->merge(['subjects' => $subjects]);
        }
    }
}
