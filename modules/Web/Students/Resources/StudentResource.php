<?php

namespace BasicDashboard\Web\Students\Resources;

use BasicDashboard\Web\Classes\Resources\ClassResource;
use BasicDashboard\Web\Grades\Resources\GradeResource;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    public function toArray($request): array
    {
        $data = [
            'id'                => customEncoder($this->id),
            'student_code'      => $this->student_code,
            'academic_year'     => $this->academic_year?->format('Y-m-d'),
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
            'avatar'            => $this->avatar,
            'student_info'      => $this->student_info,
            
            // Academic Details
            'school_attended'   => $this->school_attended,
            'grade_attended'    => $this->grade_attended,
            'year_attended'     => $this->year_attended?->format('Y-m-d'),
            'grade_id'          => $this->grade_id ? customEncoder($this->grade_id) : null,
            
            'class'             => new ClassResource($this->whenLoaded('class')),
            'grade'             => new GradeResource($this->whenLoaded('grade')),
            
            'additional_documents' => $this->documents->mapWithKeys(function ($doc) {
                return [$doc->type => $doc->url];
            }),
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
