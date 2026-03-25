<?php

namespace BasicDashboard\Web\Students\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                => customEncoder($this->id),
            'student_code'      => $this->student_code,
            'first_name'        => $this->first_name,
            'last_name'         => $this->last_name,
            'fullname'          => $this->first_name . ' ' . $this->last_name,
            'email'             => $this->email,
            'gender'            => $this->gender,
            'dob'               => $this->dob?->format('Y-m-d'),
            'registration_date' => $this->registration_date?->format('Y-m-d'),
            'status'            => $this->status, // If status exists
        ];
    }
}
