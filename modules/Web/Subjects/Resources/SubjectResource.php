<?php

namespace BasicDashboard\Web\Subjects\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubjectResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'     => customEncoder($this->id),
            'name'   => $this->name,
            'code'   => $this->code,
            'status' => $this->status,
        ];
    }
}
