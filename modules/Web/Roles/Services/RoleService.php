<?php
namespace BasicDashboard\Web\Roles\Services;

use BasicDashboard\Foundations\Domain\Roles\Role;
use BasicDashboard\Foundations\Shared\BaseCrudService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class RoleService extends BaseCrudService
{
    protected bool $useDecoder = false;

    public function __construct(
        Role $role,
        private Permission $permission,
    ) {
        parent::__construct($role);
    }

    public function getFormattedPermissions(): array
    {
        $features = config('numbers.permissions');
        $permissions = $this->permission->orderBy('id', 'asc')->get(['id', 'name'])->toArray(); 
        $finalPermissions = [];
        foreach ($features as $feature) {
            $finalPermissions[$feature] = array_filter($permissions, function ($permission) use ($feature) {
                $getPermissionFeature = explode(' ', $permission['name'])[1];
                return $getPermissionFeature == $feature;
            });
        }
        return $finalPermissions;
    }

    public function store(array $request): Model
    {
        return DB::transaction(function () use ($request) {
            $role = parent::store([
                'name'             => $request['name'],
                'guard_name'       => 'web',
                'can_access_panel' => $request['can_access_panel'],
            ]);
            $role->givePermissionTo($request['permissions']);
            return $role;
        });
    }

    public function findWithPermissions(string $id): array
    {
        $role = parent::findOrFail($id);
        $getAllPermissions     = $this->getFormattedPermissions();                     
        $getCurrentPermissions = $role->getAllPermissions()->pluck('name')->toArray(); 
        return [
            'role' => $role,
            'getAllPermissions' => $getAllPermissions,
            'getCurrentPermissions' => $getCurrentPermissions,
        ];
    }

    public function update(array $request, string $id): Model
    {
        return DB::transaction(function () use ($request, $id) {
            $role = parent::update([
                'name'             => $request['name'],
                'can_access_panel' => $request['can_access_panel'] ?? 0,
            ], $id);
            $role->syncPermissions($request['permissions']); 
            return $role;
        });
    }
}
