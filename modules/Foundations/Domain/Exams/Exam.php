<?php

namespace BasicDashboard\Foundations\Domain\Exams;

use BasicDashboard\Foundations\Domain\AcademicSessions\AcademicSession;
use BasicDashboard\Foundations\Domain\Marks\Mark;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exam extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'academic_session_id',
        'name',
        'term',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function marks()
    {
        return $this->hasMany(Mark::class);
    }

    public function academicSession()
    {
        return $this->belongsTo(AcademicSession::class);
    }

    public function scopeFilterByKeyword($query, ?string $keyword)
    {
        return $query->when($keyword, function ($q) use ($keyword) {
            $q->where('name', 'like', "%{$keyword}%")
              ->orWhereHas('academicSession', function ($q) use ($keyword) {
                  $q->where('name', 'like', "%{$keyword}%");
              });
        });
    }

    public function scopeOrderByLatest($query)
    {
        return $query->latest();
    }
}
