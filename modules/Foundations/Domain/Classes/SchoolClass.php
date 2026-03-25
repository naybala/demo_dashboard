<?php

namespace BasicDashboard\Foundations\Domain\Classes;

use BasicDashboard\Foundations\Domain\Grades\Grade;
use BasicDashboard\Foundations\Domain\Students\Student;
use BasicDashboard\Foundations\Domain\Subjects\Subject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchoolClass extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'classes';

    protected $fillable = [
        'grade_id',
        'name',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_class');
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'class_subjects');
    }

    public function scopeFilterByKeyword($query, ?string $keyword)
    {
        return $query->when($keyword, function ($q) use ($keyword) {
            $q->where('name', 'like', "%{$keyword}%")
              ->orWhereHas('grade', function ($q) use ($keyword) {
                  $q->where('name', 'like', "%{$keyword}%");
              });
        });
    }

    public function scopeOrderByLatest($query)
    {
        return $query->latest();
    }
}
