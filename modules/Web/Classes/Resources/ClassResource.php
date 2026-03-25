<?php

namespace BasicDashboard\Web\Classes\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClassResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'       => customEncoder($this->id),
            'name'     => $this->name,
            'status'   => $this->status,
            'grade_id' => customEncoder($this->grade_id),
            'grade_name' => $this->grade?->name,
        ];
    }
}
