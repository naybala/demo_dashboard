<?php

namespace BasicDashboard\Foundations\Domain\Documents;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'type',
        'file_path',
        'owner_id',
        'owner_type',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function owner()
    {
        return $this->morphTo();
    }

    public function setFilePathAttribute($value)
    {
        if ($value) {
            $cloudUrl = config('config.cloud_url');
            if ($cloudUrl && str_starts_with($value, $cloudUrl)) {
                $value = str_replace(rtrim($cloudUrl, '/') . '/', '', $value);
            }
        }
        $this->attributes['file_path'] = $value;
    }

    public function getUrlAttribute()
    {
        return $this->file_path ? Storage::disk('s3')->url($this->file_path) : null;
    }
}
