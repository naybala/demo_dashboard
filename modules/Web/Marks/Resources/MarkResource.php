<?php

namespace BasicDashboard\Web\Marks\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MarkResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'             => customEncoder($this->id),
            'student_id'     => customEncoder($this->student_id),
            'student_name'   => $this->student?->name,
            'exam_id'        => customEncoder($this->exam_id),
            'exam_name'      => $this->exam?->name,
            'subject_id'     => customEncoder($this->subject_id),
            'subject_name'   => $this->subject?->name,
            'marks_obtained' => $this->marks_obtained,
            'grade'          => $this->grade,
            'status'         => $this->status,
        ];
    }
}
