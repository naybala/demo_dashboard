<?php

namespace BasicDashboard\Web\Grades\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GradeResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'     => customEncoder($this->id),
            'name'   => $this->name,
            'code'   => $this->code,
            'status' => $this->status,
            'can_be_deleted' => true, // Add logic if needed
        ];
    }
}
