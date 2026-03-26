<?php

namespace BasicDashboard\Web\Users\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

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
                'marital_status'       => $this->marital_status ?? null,
                'place_of_birth'       => $this->place_of_birth ?? null,
                'nrc'                  => $this->nrc ?? null,
                'religion'             => $this->religion ?? null,
                'nationality'          => $this->nationality ?? null,
                'professional_subject' => $this->professional_subject ?? null,
                'possessive_grade'     => $this->possessive_grade ?? null,
                'current_address'      => $this->current_address ?? null,
                'permanent_address'    => $this->permanent_address ?? null,
                'education_background' => $this->education_background ?? null,
                'work_experience'      => $this->work_experience ?? null,
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
                ] : null;
            })(),
            'mother'       => (function() {
                $mother = $this->guardians->where('relation', 'mother')->first();
                return $mother ? [
                    'name'         => $mother->name,
                    'nrc'          => $mother->nrc,
                    'phone'        => $mother->phone,
                    'address'      => $mother->address,
                ] : null;
            })(),
            'class_id'     => (function() {
                $classObj = DB::table('classes')->where('head_teacher_id', $this->id)->first();
                return $classObj ? $classObj->id : null;
            })(),
        ];
    }
}
