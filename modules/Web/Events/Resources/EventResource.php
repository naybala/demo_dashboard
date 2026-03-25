<?php

namespace BasicDashboard\Web\Events\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'             => $this->id,
            'title'          => $this->title,
            'description'    => $this->description,
            'date'           => $this->date->format('Y-m-d'),
            'date_formatted' => $this->date->format('d M Y'),
            'start_time'     => $this->start_time,
            'end_time'       => $this->end_time,
            'time_formatted' => $this->formatTimeRange(),
            'location'       => $this->location,
            'type'           => $this->type->value,
            'type_label'     => $this->type->label(),
        ];
    }

    private function formatTimeRange()
    {
        if (!$this->start_time) return '';
        $start = \Carbon\Carbon::createFromFormat('H:i:s', $this->start_time)->format('g:i a');
        $end = $this->end_time ? \Carbon\Carbon::createFromFormat('H:i:s', $this->end_time)->format('g:i a') : '';
        return $end ? "{$start} - {$end}" : $start;
    }
}
