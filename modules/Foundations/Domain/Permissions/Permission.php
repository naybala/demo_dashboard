<?php
namespace BasicDashboard\Foundations\Domain\Permissions;

use App\Observers\AuditObserver;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Permission as SpatiePermission;

#[ObservedBy([AuditObserver::class])]
#[Fillable([
    'name',
    'guard_name',
    'created_at',
    'updated_at',
])]
class Permission extends SpatiePermission
{
    use HasFactory;

    protected static function newFactory()
    {
        return \Database\Factories\PermissionFactory::new();
    }

    

    /**
     * Scope to filter permissions by keyword search
     * searches in: name
     * 
     * Usage: Permission::filterByKeyword($keyword)->get()
     */
    /**
     * Scope to filter permissions by keyword search
     */
    public function scopeFilterByKeyword(Builder $query, ?string $keyword): Builder
    {
        return $query->when($keyword, function (Builder $q) use ($keyword) {
            $q->where('name', 'LIKE', '%' . $keyword . '%');
        });
    }

    /**
     * Scope to order permissions by latest activity
     */
    public function scopeOrderByLatest(Builder $query): Builder
    {
        return $query->orderByRaw('CASE WHEN created_at IS NULL THEN updated_at ELSE created_at END DESC')
            ->orderByDesc('id');
    }

    public function hasRoles(): bool
    {
        return $this->roles()->exists();
    }
}
