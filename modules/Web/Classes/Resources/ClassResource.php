<?php

namespace BasicDashboard\Web\Classes\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClassResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                => customEncoder($this->id),
            'name'              => $this->name,
            'grade_id'          => customEncoder($this->grade_id),
            'grade_name'        => $this->grade?->name,
            'session_id'        => $this->session_id ? customEncoder($this->session_id) : null,
            'session_name'      => $this->session?->name,
            'section'           => $this->section,
            'capacity'          => $this->capacity,
            'teaching_days'     => $this->teaching_days,
            'start_time'        => $this->start_time,
            'end_time'          => $this->end_time,
            'attendance_mode'   => $this->attendance_mode,
            'allow_makeup_attendance' => $this->allow_makeup_attendance,
            'head_teacher_id'   => $this->head_teacher_id ? customEncoder($this->head_teacher_id) : null,
            'head_teacher_name' => $this->headTeacher?->fullname,
            'co_teacher_id'     => $this->co_teacher_id ? customEncoder($this->co_teacher_id) : null,
            'co_teacher_name'   => $this->coTeacher?->fullname,
            'notes'             => $this->notes,
            'status'            => $this->status,
            'subjects'          => $this->subjects->map(fn($s) => [
                'id'             => customEncoder($s->id),
                'name'           => $s->name,
                'teacher_id'     => $s->pivot->teacher_id ? customEncoder($s->pivot->teacher_id) : null,
                'teacher_name'   => $s->pivot->teacher_id ? \BasicDashboard\Foundations\Domain\Users\User::find($s->pivot->teacher_id)?->fullname : null,
                'hours_per_week' => $s->pivot->hours_per_week,
            ]),
        ];
    }
}
