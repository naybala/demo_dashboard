<?php

namespace BasicDashboard\Foundations\Domain\Classes;

use BasicDashboard\Foundations\Domain\AcademicSessions\AcademicSession;
use BasicDashboard\Foundations\Domain\Grades\Grade;
use BasicDashboard\Foundations\Domain\Students\Student;
use BasicDashboard\Foundations\Domain\Subjects\Subject;
use BasicDashboard\Foundations\Domain\Users\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchoolClass extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'classes';

    protected $fillable = [
        'grade_id',
        'session_id',
        'section',
        'name',
        'capacity',
        'teaching_days',
        'start_time',
        'end_time',
        'attendance_mode',
        'head_teacher_id',
        'co_teacher_id',
        'allow_makeup_attendance',
        'notes',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function session()
    {
        return $this->belongsTo(AcademicSession::class, 'session_id');
    }

    public function headTeacher()
    {
        return $this->belongsTo(User::class, 'head_teacher_id');
    }

    public function coTeacher()
    {
        return $this->belongsTo(User::class, 'co_teacher_id');
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_class', 'class_id', 'student_id');
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'class_subjects', 'class_id', 'subject_id')
                    ->withPivot('teacher_id', 'hours_per_week')
                    ->withTimestamps();
    }

    public function scopeFilterByKeyword($query, ?string $keyword)
    {
        return $query->when($keyword, function ($q) use ($keyword) {
            $q->where('name', 'like', "%{$keyword}%")
              ->orWhere('section', 'like', "%{$keyword}%")
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
