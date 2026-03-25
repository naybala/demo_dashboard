<?php

namespace BasicDashboard\Web\AcademicSessions\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AcademicSessionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'         => customEncoder($this->id),
            'name'       => $this->name,
            'start_date' => $this->start_date?->format('Y-m-d'),
            'end_date'   => $this->end_date?->format('Y-m-d'),
            'status'     => $this->status,
        ];
    }
}
