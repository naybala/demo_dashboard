<?php

namespace BasicDashboard\Foundations\Domain\Marks;

use BasicDashboard\Foundations\Domain\Exams\Exam;
use BasicDashboard\Foundations\Domain\Students\Student;
use BasicDashboard\Foundations\Domain\Subjects\Subject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mark extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'student_id',
        'exam_id',
        'subject_id',
        'marks_obtained',
        'grade',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function scopeFilterByKeyword($query, ?string $keyword)
    {
        return $query->when($keyword, function ($q) use ($keyword) {
            $q->whereHas('student', function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%");
            })->orWhereHas('exam', function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%");
            })->orWhereHas('subject', function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%");
            });
        });
    }

    public function scopeOrderByLatest($query)
    {
        return $query->latest();
    }
}
