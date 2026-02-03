<?php
namespace BasicDashboard\Foundations\Domain\Users;

use App\Enums\Common\Status;
use App\Enums\Users\UserType;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

// #[ObservedBy([AuditObserver::class])]
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

    

}
