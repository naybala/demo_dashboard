<?php

namespace BasicDashboard\Web\Students\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    public function toArray($request): array
    {
        $data = [
            'id'                => customEncoder($this->id),
            'student_code'      => $this->student_code,
            'academic_year'     => $this->academic_year,
            'class_id'          => $this->class_id ? customEncoder($this->class_id) : null,
            'full_name'         => $this->full_name,
            'first_name'        => $this->first_name,
            'last_name'         => $this->last_name,
            'other_name'        => $this->other_name,
            'email'             => $this->email,
            'gender'            => $this->gender,
            'dob'               => $this->dob?->format('Y-m-d'),
            'nrc'               => $this->nrc,
            'place_of_birth'    => $this->place_of_birth,
            'nationality'       => $this->nationality,
            'religion'          => $this->religion,
            'address'           => $this->address,
            'registration_date' => $this->registration_date?->format('Y-m-d'),
            
            // Academic Details
            'school_attended'   => $this->school_attended,
            'grade_attended'    => $this->grade_attended,
            'year_attended'     => $this->year_attended,
            'grade_id'          => $this->grade_id ? customEncoder($this->grade_id) : null,
        ];

        // Flatten Guardians info for easy consumer access
        foreach ($this->guardians as $guardian) {
            $prefix = $guardian->relation;
            $data["{$prefix}_name"]          = $guardian->name;
            $data["{$prefix}_nrc"]           = $guardian->nrc;
            $data["{$prefix}_qualification"] = $guardian->qualification;
            $data["{$prefix}_job"]           = $guardian->job;
            $data["{$prefix}_phone"]         = $guardian->phone;
            $data["{$prefix}_email"]         = $guardian->email;
            $data["{$prefix}_address"]       = $guardian->address;
            $data["{$prefix}_alive_status"]  = $guardian->alive_status;
        }

        return $data;
    }
}
