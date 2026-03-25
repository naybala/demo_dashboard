<?php

namespace BasicDashboard\Foundations\Domain\Guardians;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Guardian extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'owner_id',
        'owner_type',
        'relation',
        'name',
        'nrc',
        'qualification',
        'job',
        'phone',
        'email',
        'address',
        'alive_status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function owner()
    {
        return $this->morphTo();
    }
}
