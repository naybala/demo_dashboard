<?php
namespace BasicDashboard\Foundations\Domain\Roles;

use App\Observers\AuditObserver;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Spatie\Permission\Models\Role as SpatieRole;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy([AuditObserver::class])]
#[Fillable([
    'name',
    'guard_name',
    'can_access_panel',
    'created_at',
    'updated_at',
])]
class Role extends SpatieRole
{
    use HasFactory,SoftDeletes;

    protected static function newFactory()
    {
        return \Database\Factories\RoleFactory::new();
    }
      //protected $table = 'table_name';

    // ==========================================
    // Query Scopes for Simplified Architecture
    // ==========================================
    // These scopes replace repository methods, allowing direct Eloquent usage in services
    
    /**
     * Scope to filter roles by keyword search
     * Searches in: name
     * 
     * Usage: Role::filterByKeyword($keyword)->get()
     */
    /**
     * Scope to filter roles by keyword search
     */
    public function scopeFilterByKeyword(Builder $query, ?string $keyword): Builder
    {
        return $query->when($keyword, function (Builder $q) use ($keyword) {
            $q->where('name', 'LIKE', '%' . $keyword . '%');
        });
    }

    /**
     * Scope to order roles by latest activity
     */
    public function scopeOrderByLatest(Builder $query): Builder
    {
        return $query->orderByRaw('CASE WHEN created_at IS NULL THEN updated_at ELSE created_at END DESC')
            ->orderByDesc('id');
    }

    public function hasUsers(): bool
    {
        return $this->users()->exists();
    }

}
