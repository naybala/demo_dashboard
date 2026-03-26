<?php

namespace BasicDashboard\Web\Users\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
            'role_marked'  => $this->role_marked,
            'roles'        => $this->whenLoaded('roles', function () {
                return $this->roles->map(function ($r) {
                    return ['id' => $r->id, 'name' => $r->name];
                });
            }),
            'gender'       => $this->gender,
            'dob'          => $this->dob,
            'avatar'       => $this->avatar,
            'user_type'    => $this->user_type->value,
            'profile'      => [
                'marital_status'       => $this->marital_status ?? null,
                'place_of_birth'       => $this->place_of_birth ?? null,
                'nrc'                  => $this->nrc ?? null,
                'religion'             => $this->religion ?? null,
                'nationality'          => $this->nationality ?? null,
                'professional_subject' => $this->professional_subject ?? null,
                'possessive_grade'     => $this->possessive_grade ?? null,
                'current_address'      => $this->current_address ?? null,
                'permanent_address'    => $this->permanent_address ?? null,
                'education_background' => [
                    'year'           => $this->year,
                    'degree'         => $this->degree,
                    'certificate'    => $this->certificate,
                    'institution'    => $this->institution,
                    'specialization' => $this->specialization,
                ],
                'work_experience'      => [
                    'years'      => $this->work_years,
                    'duration'   => $this->duration,
                    'location'   => $this->location,
                    'department' => $this->department_name,
                    'position'   => $this->position,
                ],
                'professional_qualifications' => $this->professional_qualifications ?? null,
                'department_name'      => $this->department_name ?? null,
                'position'             => $this->position ?? null,
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
            'father'       => (function() {
                $father = $this->guardians->where('relation', 'father')->first();
                return $father ? [
                    'name'         => $father->name,
                    'nrc'          => $father->nrc,
                    'phone'        => $father->phone,
                    'address'      => $father->address,
                    'alive_status' => $father->alive_status,
                ] : null;
            })(),
            'mother'       => (function() {
                $mother = $this->guardians->where('relation', 'mother')->first();
                return $mother ? [
                    'name'         => $mother->name,
                    'nrc'          => $mother->nrc,
                    'phone'        => $mother->phone,
                    'address'      => $mother->address,
                    'alive_status' => $mother->alive_status,
                ] : null;
            })(),
            'class_id'     => (function() {
                $classObj = DB::table('classes')->where('head_teacher_id', $this->id)->first();
                return $classObj ? $classObj->id : null;
            })(),
        ];
    }


}
