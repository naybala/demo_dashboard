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

/**
 * RoleService - Simplified Architecture
 * 
 * This service demonstrates the refactored pattern:
 * - Uses Eloquent models directly (no Repository pattern)
 * - Leverages query scopes defined in Role model
 * - Maintains business logic separation from controllers
 * - Keeps transaction management
 */
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

    /**
     * Display paginated list of roles with optional keyword filtering
     * 
     * Uses Eloquent scopes: filterByKeyword, orderByLatest
     */
    public function index(array $request): View
    {
        // Direct Eloquent query using model scopes (no repository)
        $roleList = $this->role
            ->filterByKeyword($request['keyword'] ?? null)  // Apply search filter
            ->orderByLatest()                                // Order by latest activity
            ->paginate($request['paginate'] ?? 20);

        $roleList = RoleResource::collection($roleList)->response()->getData(true);
        return $this->responseFactory->successView(self::VIEW . '.index', $roleList);
    }

    public function create(): View
    {
        $getAllPermissions = $this->getFormattedPermissions();
        return view(self::VIEW . '.create', compact('getAllPermissions'));
    }

    /**
     * Create a new role with permissions
     * 
     * Uses DB transactions directly instead of repository transaction methods
     */
    public function store($request): RedirectResponse
    {
        try {
            \DB::beginTransaction();  // Direct DB transaction instead of repository method
            
            // Direct Eloquent create instead of repository insert
            $role = $this->role->create([
                'name'             => $request['name'],
                'guard_name'       => 'web',
                'can_access_panel' => $request['can_access_panel'],
                'created_by'       => Auth::id(),  // Add created_by manually (was in BaseRepository)
            ]);
            $role->givePermissionTo($request['permissions']);
            
            \DB::commit();  // Direct DB commit
            return $this->responseFactory->successIndexRedirect(self::ROUTE, __(self::LANG_PATH . '_created'));
        } catch (Exception $e) {
            \DB::rollBack();  // Direct DB rollback
            return $this->responseFactory->redirectBackWithError($e->getMessage());
        }
    }

    /**
     * Edit role - fetch role data for editing
     */
    public function edit(string $id): View | RedirectResponse
    {
        $id = customDecoder($id);  // Decode ID (was in BaseRepository)
        $role = $this->role->findOrFail($id);
        
        $getAllPermissions     = $this->getFormattedPermissions();                     //get all permissions
        $getCurrentPermissions = $role->getAllPermissions()->pluck('name')->toArray(); //get permission of this role
        $role                  = new RoleResource($role);
        $role                  = $role->response()->getData(true)['data'];
        $data                  = [
            'role'                  => $role,
            'getAllPermissions'     => $getAllPermissions,
            'getCurrentPermissions' => $getCurrentPermissions,
        ];
        return $this->responseFactory->successView(self::VIEW . ".edit", $data);        
    }

    /**
     * Show role details
     */
    public function show(string $id): View | RedirectResponse
    {
        $id = customDecoder($id);
        $role = $this->role->findOrFail($id);
        $role = new RoleResource($role);
        $role = $role->response()->getData(true)['data'];
        return $this->responseFactory->successView(self::VIEW . '.show', $role);
    }

    /**
     * Update role with permissions
     */
    public function update($request, string $id): RedirectResponse
    {
        try {
            \DB::beginTransaction();
            
            $decodedId = customDecoder($id);
            $role = $this->role->findOrFail($decodedId);
            
            // Direct Eloquent update
            $role->update([
                'name'             => $request['name'],
                'can_access_panel' => $request['can_access_panel'],
            ]);
            $role->syncPermissions($request['permissions']); // this will detach and attach permissions to this role
            
            \DB::commit();
            return $this->responseFactory->redirectRoute(self::ROUTE . ".index", __(self::LANG_PATH . '_updated'));
        } catch (Exception $e) {
            \DB::rollBack();
            return $this->responseFactory->redirectBackWithError($e->getMessage());
        }
    }

    /**
     * Delete role
     */
    public function destroy($request): RedirectResponse
    {
        try {
            \DB::beginTransaction();
            
            $id = customDecoder($request['id']); // we will need to convert to real ID from encodeId
            
            // Direct Eloquent delete
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
                //get "users" from "manage users","create users",etc
                $getPermissionFeature = explode(' ', $permission['name'])[1];
                return $getPermissionFeature == $feature;
            });
        }
        return $finalPermissions;
    }
}
