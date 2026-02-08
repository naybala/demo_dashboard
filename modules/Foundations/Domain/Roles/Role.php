<?php
namespace BasicDashboard\Foundations\Domain\Roles;

use App\Observers\AuditObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Spatie\Permission\Models\Role as SpatieRole;

#[ObservedBy([AuditObserver::class])]
class Role extends SpatieRole
{
    use HasFactory;

    protected static function newFactory()
    {
        return \Database\Factories\RoleFactory::new();
    }
      //protected $table = 'table_name';
    protected $fillable = [
        'name',
        'guard_name',
        'can_access_panel',
        'created_at',
        'updated_at',
    ];

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
    public function scopeFilterByKeyword($query, ?string $keyword)
    {
        if (empty($keyword)) {
            return $query;
        }

        return $query->where('name', 'LIKE', '%' . $keyword . '%');
    }

    /**
     * Scope to order roles by latest activity
     * Orders by created_at or updated_at (whichever is more recent), then by id
     * 
     * Usage: Role::orderByLatest()->get()
     */
    public function scopeOrderByLatest($query)
    {
        return $query->orderByRaw('CASE WHEN created_at IS NULL THEN updated_at ELSE created_at END DESC')
            ->orderBy('id', 'desc');
    }

}
