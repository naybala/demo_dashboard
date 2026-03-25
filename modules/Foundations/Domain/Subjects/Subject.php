<?php

namespace BasicDashboard\Foundations\Domain\Subjects;

use BasicDashboard\Foundations\Domain\Classes\SchoolClass;
use BasicDashboard\Foundations\Domain\Marks\Mark;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'type',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function classes()
    {
        return $this->belongsToMany(SchoolClass::class, 'class_subjects');
    }

    public function marks()
    {
        return $this->hasMany(Mark::class);
    }

    public function scopeFilterByKeyword($query, ?string $keyword)
    {
        return $query->when($keyword, function ($q) use ($keyword) {
            $q->where('name', 'like', "%{$keyword}%")
              ->orWhere('code', 'like', "%{$keyword}%");
        });
    }

    public function scopeOrderByLatest($query)
    {
        return $query->latest();
    }
}
