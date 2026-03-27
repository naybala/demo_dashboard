<?php

namespace BasicDashboard\Foundations\Domain\Students;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use BasicDashboard\Foundations\Domain\Classes\SchoolClass;
use BasicDashboard\Foundations\Domain\Grades\Grade;
use BasicDashboard\Foundations\Domain\Guardians\Guardian;
use BasicDashboard\Foundations\Domain\Marks\Mark;


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
        'student_info',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'dob'               => 'date',
        'registration_date' => 'date',
        'academic_year'     => 'date',
        'year_attended'     => 'date',
    ];

    public function getProfilePhotoAttribute($value)
    {
        return $value ? \Illuminate\Support\Facades\Storage::disk('s3')->url($value) : 'upload/profile.png';
    }

    public function setProfilePhotoAttribute($value)
    {
        if ($value) {
            $cloudUrl = config('config.cloud_url');
            if ($cloudUrl && str_starts_with($value, $cloudUrl)) {
                $value = str_replace(rtrim($cloudUrl, '/') . '/', '', $value);
            }
            if ($value === 'upload/profile.png' || $value === rtrim($cloudUrl, '/') . '/' || empty($value)) {
                $value = null;
            }
        }
        $this->attributes['profile_photo'] = $value;
    }

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
