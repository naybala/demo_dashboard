<?php

namespace BasicDashboard\Foundations\Domain\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'user_id',
    'marital_status',
    'place_of_birth',
    'nrc',
    'religion',
    'nationality',
    'professional_subject',
    'possessive_grade',
    'current_address',
    'permanent_address',
    'education_background',
    'work_experience',
    'professional_qualifications',
    'department_name',
    'position',
    'service_duration',
])]
class UserProfile extends Model
{
    protected $casts = [
        'education_background'        => 'array',
        'work_experience'             => 'array',
        'professional_qualifications' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
