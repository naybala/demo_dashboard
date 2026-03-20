<?php
namespace BasicDashboard\Foundations\Domain\Users;

use App\Enums\Common\Status;
use App\Enums\Users\UserType;
use App\Observers\AuditObserver;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy([AuditObserver::class])]
#[Fillable([
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
])]

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

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
    /**
     * Scope to filter users by keyword search
     */
    public function scopeFilterByKeyword(Builder $query, ?string $keyword): Builder
    {
        return $query->when($keyword, function (Builder $q) use ($keyword) {
            $keyword = strtolower($keyword);
            $status_text = [
                'active'   => 1,
                'inactive' => 2,
            ];

            $q->where(function (Builder $sub) use ($keyword, $status_text) {
                $sub->where(function (Builder $subInner) use ($keyword, $status_text) {
                    $subInner->where('fullname', 'like', "%{$keyword}%")
                        ->orWhere('email', 'like', "%{$keyword}%")
                        ->orWhere('phone_number', 'like', "%{$keyword}%")
                        ->orWhere('role_marked', 'like', "%{$keyword}%");

                    if (isset($status_text[$keyword])) {
                        $subInner->orWhere('status', $status_text[$keyword]);
                    } else {
                        $subInner->orWhere('status', 'like', "%{$keyword}%");
                    }
                })
                ->orWhereHas('roles', function (Builder $roleQuery) use ($keyword) {
                    $roleQuery->where('name', 'like', "%{$keyword}%");
                })
                ->orWhereHas('groups', function (Builder $groupQuery) use ($keyword) {
                    $groupQuery->where('name', 'like', "%{$keyword}%");
                });
            });
        });
    }

    /**
     * Scope to eager load common user relationships
     */
    public function scopeWithUserRelations(Builder $query): Builder
    {
        return $query->with(['roles']);
    }

    /**
     * Scope to order users by latest activity
     */
    public function scopeOrderByLatest(Builder $query): Builder
    {
        return $query->orderByRaw('CASE WHEN created_at IS NULL THEN updated_at ELSE created_at END DESC')
            ->orderByDesc('id');
    }

}
