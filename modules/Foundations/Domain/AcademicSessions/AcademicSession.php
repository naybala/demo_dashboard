<?php

namespace BasicDashboard\Foundations\Domain\AcademicSessions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcademicSession extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'status'     => \App\Enums\Common\Status::class,
    ];

    public function scopeFilterByKeyword($query, ?string $keyword)
    {
        return $query->when($keyword, function ($q) use ($keyword) {
            $q->where('name', 'like', "%{$keyword}%");
        });
    }

    public function scopeOrderByLatest($query)
    {
        return $query->latest();
    }
}
