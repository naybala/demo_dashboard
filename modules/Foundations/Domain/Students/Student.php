<?php

namespace BasicDashboard\Foundations\Domain\Students;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use BasicDashboard\Foundations\Domain\Classes\SchoolClass;
use BasicDashboard\Foundations\Domain\Grades\Grade;


class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'student_code',
        'academic_year',
        'class_id',
        'full_name',
        'first_name',
        'last_name',
        'other_name',
        'email',
        'gender',
        'dob',
        'nrc',
        'place_of_birth',
        'nationality',
        'religion',
        'address',
        'registration_date',
        'profile_photo',
        'school_attended',
        'grade_attended',
        'year_attended',
        'grade_id',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'dob'               => 'date',
        'registration_date' => 'date',
    ];

    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class, 'grade_id');
    }

    public function guardians()
    {
        return $this->morphMany(Guardian::class, 'owner');
    }

    public function marks()
    {
        return $this->hasMany(Mark::class);
    }

    public function scopeFilterByKeyword($query, ?string $keyword)
    {
        return $query->when($keyword, function ($q) use ($keyword) {
            $q->where('full_name', 'like', "%{$keyword}%")
              ->orWhere('first_name', 'like', "%{$keyword}%")
              ->orWhere('last_name', 'like', "%{$keyword}%")
              ->orWhere('student_code', 'like', "%{$keyword}%")
              ->orWhere('nrc', 'like', "%{$keyword}%")
              ->orWhere('email', 'like', "%{$keyword}%");
        });
    }

    public function scopeOrderByLatest($query)
    {
        return $query->latest();
    }
}
