<?php

namespace BasicDashboard\Web\Exams\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExamResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                  => customEncoder($this->id),
            'name'                => $this->name,
            'academic_session_id' => customEncoder($this->academic_session_id),
            'academic_session_name' => $this->academicSession?->name,
            'term'                => $this->term,
            'status'              => $this->status,
        ];
    }
}
