<?php

namespace BasicDashboard\Foundations\Domain\Announcements;

use App\Enums\Announcements\AnnouncementDepartment;
use App\Enums\Announcements\AnnouncementDestination;
use App\Enums\Common\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Announcement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'department',
        'date',
        'destination',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'status'      => Status::class,
        'department'  => AnnouncementDepartment::class,
        'destination' => AnnouncementDestination::class,
        'date'        => 'date',
    ];

    public function scopeFilterByKeyword($query, ?string $keyword)
    {
        return $query->when($keyword, function ($q) use ($keyword) {
            $q->where('title', 'like', "%{$keyword}%")
              ->orWhere('description', 'like', "%{$keyword}%");
        });
    }

    public function scopeOrderByLatest($query)
    {
        return $query->latest();
    }
}
