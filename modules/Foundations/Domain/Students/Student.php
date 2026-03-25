<?php

namespace BasicDashboard\Foundations\Domain\Students;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'student_code',
        'first_name',
        'last_name',
        'other_name',
        'email',
        'gender',
        'dob',
        'place_of_birth',
        'nationality',
        'religion',
        'address',
        'registration_date',
        'profile_photo',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'dob'               => 'date',
        'registration_date' => 'date',
    ];

    public function classes()
    {
        return $this->belongsToMany(SchoolClass::class, 'student_class');
    }

    public function marks()
    {
        return $this->hasMany(Mark::class);
    }

    public function scopeFilterByKeyword($query, ?string $keyword)
    {
        return $query->when($keyword, function ($q) use ($keyword) {
            $q->where('first_name', 'like', "%{$keyword}%")
              ->orWhere('last_name', 'like', "%{$keyword}%")
              ->orWhere('student_code', 'like', "%{$keyword}%")
              ->orWhere('email', 'like', "%{$keyword}%");
        });
    }

    public function scopeOrderByLatest($query)
    {
        return $query->latest();
    }
}
