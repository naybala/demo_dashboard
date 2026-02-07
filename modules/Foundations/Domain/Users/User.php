<?php
namespace BasicDashboard\Foundations\Domain\Users;

use App\Enums\Common\Status;
use App\Enums\Users\UserType;
use App\Observers\AuditObserver;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

#[ObservedBy([AuditObserver::class])]
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $guard_name = 'web';
    protected static function newFactory()
    {
        return UserFactory::new ();
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'status'    => Status::class,
        'user_type' => UserType::class,
    ];

    protected $fillable = [
        "fullname",
        "email",
        "password",
        "status",
        "user_type",
        "phone_number",
        "avatar",
        "role_marked",
        "remember_token",
        "created_at",
        "updated_at",
        "deleted_at",
        "created_by",
        "updated_by",
        "deleted_by",
    ];

    public function getAvartarAttribute($value)
    {
        return 'upload/profile.png';
    }

    // ==========================================
    // Query Scopes for Simplified Architecture
    // ==========================================
    // These scopes replace repository methods, allowing direct Eloquent usage in services
    
    /**
     * Scope to filter users by keyword search
     * Searches across: fullname, email, phone_number, role_marked, status, roles.name, groups.name
     * 
     * Usage: User::filterByKeyword($keyword)->get()
     */
    public function scopeFilterByKeyword($query, ?string $keyword)
    {
        if (empty($keyword)) {
            return $query;
        }

        $keyword = strtolower($keyword);
        $status_text = [
            'active'   => 1,
            'inactive' => 2,
        ];

        return $query->where(function ($q) use ($keyword, $status_text) {
            $q->where(function ($subQuery) use ($keyword, $status_text) {
                $subQuery->where('fullname', 'like', "%{$keyword}%")
                    ->orWhere('email', 'like', "%{$keyword}%")
                    ->orWhere('phone_number', 'like', "%{$keyword}%")
                    ->orWhere('role_marked', 'like', "%{$keyword}%");

                // Interpret 'active'/'inactive' text into status code
                if (isset($status_text[$keyword])) {
                    $subQuery->orWhere('status', $status_text[$keyword]);
                } else {
                    $subQuery->orWhere('status', 'like', "%{$keyword}%");
                }
            });

            // Match roles.name
            $q->orWhereHas('roles', function ($roleQuery) use ($keyword) {
                $roleQuery->where('name', 'like', "%{$keyword}%");
            });

            // Match groups.name (optional, if needed)
            $q->orWhereHas('groups', function ($groupQuery) use ($keyword) {
                $groupQuery->where('name', 'like', "%{$keyword}%");
            });
        });
    }

    /**
     * Scope to eager load common user relationships
     * 
     * Usage: User::withUserRelations()->get()
     */
    public function scopeWithUserRelations($query)
    {
        return $query->with(['roles']);
    }

    /**
     * Scope to order users by latest activity
     * Orders by created_at or updated_at (whichever is more recent), then by id
     * 
     * Usage: User::orderByLatest()->get()
     */
    public function scopeOrderByLatest($query)
    {
        return $query->orderByRaw('CASE WHEN created_at IS NULL THEN updated_at ELSE created_at END DESC')
            ->orderBy('id', 'desc');
    }

}
