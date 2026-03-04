<?php
namespace BasicDashboard\Foundations\Domain\Permissions;

use App\Observers\AuditObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Spatie\Permission\Models\Permission as SpatiePermission;

#[ObservedBy([AuditObserver::class])]
class Permission extends SpatiePermission
{
    use HasFactory;

    protected $fillable = [
        'name',
        'guard_name',
        'created_at',
        'updated_at',
    ];

    /**
     * Scope to filter permissions by keyword search
     * searches in: name
     * 
     * Usage: Permission::filterByKeyword($keyword)->get()
     */
    public function scopeFilterByKeyword($query, ?string $keyword)
    {
        if (empty($keyword)) {
            return $query;
        }

        return $query->where('name', 'LIKE', '%' . $keyword . '%');
    }

    /**
     * Scope to order permissions by latest activity
     * Orders by created_at or updated_at (whichever is more recent), then by id
     * 
     * Usage: Permission::orderByLatest()->get()
     */
    public function scopeOrderByLatest($query)
    {
        return $query->orderByRaw('CASE WHEN created_at IS NULL THEN updated_at ELSE created_at END DESC')
            ->orderBy('id', 'desc');
    }
}
