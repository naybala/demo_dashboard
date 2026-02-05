<?php
namespace BasicDashboard\Web\Roles\Services;

use BasicDashboard\Foundations\Domain\Roles\Role;
use BasicDashboard\Web\Common\BaseController;
use BasicDashboard\Web\Roles\Resources\RoleResource;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;

class RoleService extends BaseController
{
    const VIEW      = 'admin.role';
    const ROUTE     = 'roles';
    const LANG_PATH = "role.role";

    public function __construct(
        private Role $role,
        private Permission $permission,
        private ResponseFactory $responseFactory,
    ) {
    }

    public function index(array $request): View
    {
        $roleList = $this->role
            ->filterByKeyword($request['keyword'] ?? null)
            ->orderByLatest()
            ->paginate($request['paginate'] ?? 20);

        $roleList = RoleResource::collection($roleList)->response()->getData(true);
        return $this->responseFactory->successView(self::VIEW . '.index', $roleList);
    }

    public function create(): View
    {
        $getAllPermissions = $this->getFormattedPermissions();
        return view(self::VIEW . '.create', compact('getAllPermissions'));
    }

    public function store($request): RedirectResponse
    {
        try {
            \DB::beginTransaction();
            $role = $this->role->create([
                'name'             => $request['name'],
                'guard_name'       => 'web',
                'can_access_panel' => $request['can_access_panel'],
                'created_by'       => Auth::id(),
            ]);
            $role->givePermissionTo($request['permissions']);
            \DB::commit(); 
            return $this->responseFactory->successIndexRedirect(self::ROUTE, __(self::LANG_PATH . '_created'));
        } catch (Exception $e) {
            \DB::rollBack();
            return $this->responseFactory->redirectBackWithError($e->getMessage());
        }
    }


    public function edit(string $id): View | RedirectResponse
    {
        $id = customDecoder($id);  
        $role = $this->role->findOrFail($id);
        $getAllPermissions     = $this->getFormattedPermissions();                     
        $getCurrentPermissions = $role->getAllPermissions()->pluck('name')->toArray(); 
        $role                  = new RoleResource($role);
        $role                  = $role->response()->getData(true)['data'];
        $data                  = [
            'role'                  => $role,
            'getAllPermissions'     => $getAllPermissions,
            'getCurrentPermissions' => $getCurrentPermissions,
        ];
        return $this->responseFactory->successView(self::VIEW . ".edit", $data);        
    }


    public function show(string $id): View | RedirectResponse
    {
        $id = customDecoder($id);
        $role = $this->role->findOrFail($id);
        $role = new RoleResource($role);
        $role = $role->response()->getData(true)['data'];
        return $this->responseFactory->successView(self::VIEW . '.show', $role);
    }


    public function update($request, string $id): RedirectResponse
    {
        try {
            \DB::beginTransaction();
            
            $decodedId = customDecoder($id);
            $role = $this->role->findOrFail($decodedId);
            $role->update([
                'name'             => $request['name'],
                'can_access_panel' => $request['can_access_panel'],
            ]);
            $role->syncPermissions($request['permissions']);
            \DB::commit();
            return $this->responseFactory->redirectRoute(self::ROUTE . ".index", __(self::LANG_PATH . '_updated'));
        } catch (Exception $e) {
            \DB::rollBack();
            return $this->responseFactory->redirectBackWithError($e->getMessage());
        }
    }

    public function destroy($request): RedirectResponse
    {
        try {
            \DB::beginTransaction();
            $id = customDecoder($request['id']);
            $this->role->destroy($id);
            \DB::commit();
            return $this->responseFactory->redirectRoute(self::ROUTE . ".index", __(self::LANG_PATH . '_deleted'));
        } catch (Exception $e) {
            \DB::rollBack();
            return $this->responseFactory->redirectBackWithError($e->getMessage());
        }
    }

    // ==========================================
    // Private Helper Methods
    // ==========================================

    /**
     * Generates a multidimensional array of permissions grouped by feature. ["users"=>array of user permission lists,"countries"=> .... ]
     * 1. get All the features and Permission Lists from database
     * 2. we will loop features and check with each feature name Eg.users
     * 3. we will add feature_key(users) to $finalPermission -> $finalPermissions['users'] on the left side
     * 4. on the right side - we will filter all permissions that include feature key('users') Eg-['manage users','create users',...]
     */
    private function getFormattedPermissions(): array
    {
        $features = config('numbers.permissions');
        $permissions      = $this->permission->orderBy('id', 'asc')->get(['id', 'name'])->toArray(); //['manage users',...] All permissions
        $finalPermissions = [];
        foreach ($features as $feature) {
            $finalPermissions[$feature] = array_filter($permissions, function ($permission) use ($feature) {
                $getPermissionFeature = explode(' ', $permission['name'])[1];
                return $getPermissionFeature == $feature;
            });
        }
        return $finalPermissions;
    }
}
