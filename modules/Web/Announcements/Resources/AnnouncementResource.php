<?php

namespace BasicDashboard\Web\Announcements\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AnnouncementResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'          => customEncoder($this->id),
            'title'       => $this->title,
            'description' => $this->description,
            'department'  => $this->department?->value,
            'department_label' => $this->department?->label(),
            'date'        => $this->date?->format('Y-m-d'),
            'date_formatted' => $this->date?->format('d M Y'),
            'destination' => $this->destination?->value,
            'destination_label' => $this->destination?->label(),
            'status'      => $this->status?->value,
            'status_label' => $this->status?->label(),
        ];
    }
}
