<?php

namespace BasicDashboard\Web\Users\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'           => customEncoder($this->id),
            'fullname'     => $this->fullname,
            'staff_id'     => $this->staff_id,
            'email'        => $this->email,
            'phone_number' => $this->phone_number,
            'gender'       => $this->gender ? $this->gender->value : null,
            'gender_label' => $this->gender ? $this->gender->label() : null,
            'dob'          => $this->dob?->format('Y-m-d'),
            'avatar'       => $this->avatar,
            'user_type'    => $this->user_type->value,
            'user_type_label' => $this->user_type->label(),
            'profile'      => [
                'marital_status'       => $this->profile->marital_status ?? null,
                'place_of_birth'       => $this->profile->place_of_birth ?? null,
                'nrc'                  => $this->profile->nrc ?? null,
                'religion'             => $this->profile->religion ?? null,
                'nationality'          => $this->profile->nationality ?? null,
                'professional_subject' => $this->profile->professional_subject ?? null,
                'possessive_grade'     => $this->profile->possessive_grade ?? null,
                'current_address'      => $this->profile->current_address ?? null,
                'permanent_address'    => $this->profile->permanent_address ?? null,
                'education_background' => $this->profile->education_background ?? null,
                'work_experience'      => $this->profile->work_experience ?? null,
                'professional_qualifications' => $this->profile->professional_qualifications ?? null,
                'department_name'      => $this->profile->department_name ?? null,
                'position'             => $this->profile->position ?? null,
            ],
            'spouse'       => (function() {
                $spouse = $this->guardians->where('relation', 'spouse')->first();
                return $spouse ? [
                    'name'         => $spouse->name,
                    'nrc'          => $spouse->nrc,
                    'job'          => $spouse->job,
                    'phone'        => $spouse->phone,
                    'email'        => $spouse->email,
                    'address'      => $spouse->address,
                    'alive_status' => $spouse->alive_status,
                ] : null;
            })(),
        ];
    }
}
