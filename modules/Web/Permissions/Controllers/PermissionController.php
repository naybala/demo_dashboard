<?php
namespace BasicDashboard\Web\Permissions\Controllers;

use BasicDashboard\Web\Common\BaseController;
use BasicDashboard\Web\Permissions\Resources\PermissionResource;
use BasicDashboard\Web\Permissions\Services\PermissionService;
use BasicDashboard\Web\Permissions\Validation\StorePermissionRequest;
use BasicDashboard\Web\Permissions\Validation\UpdatePermissionRequest;
use BasicDashboard\Web\Permissions\Validation\DeletePermissionRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;
use App\Exceptions\WarningException;
use BasicDashboard\Foundations\Domain\Permissions\Permission;

class PermissionController extends BaseController
{
    const ROUTE = 'permissions';
    const LANG_PATH = "permission.permission";

    public function __construct(
        private PermissionService $permissionService
    ) {
    }

    public function index(Request $request): Response
    {
        $permissionList = $this->permissionService->paginate($request->all());
        $permissionList = PermissionResource::collection($permissionList)->response()->getData(true);
        return Inertia::render('Permissions/Index', $permissionList);
    }

    public function create(): Response
    {
        return Inertia::render('Permissions/CreateEdit');
    }

    public function store(StorePermissionRequest $request): RedirectResponse
    {
        try {
            $this->permissionService->store($request->validated());
            return redirect()->route(self::ROUTE . '.index')->with('success', __(self::LANG_PATH . '_created'));
        } catch (Throwable $e) {
            $this->LogError("Permission store failed", $e);
            return back()->with('error', $e->getMessage());
        }
    }

    public function edit(Permission $permission): Response
    {
        return Inertia::render('Permissions/CreateEdit', [
            'permission' => new PermissionResource($permission)
        ]);
    }

    public function update(UpdatePermissionRequest $request, Permission $permission): RedirectResponse
    {
        try {
            $this->permissionService->update($request->validated(), $permission->id);
            return redirect()->route(self::ROUTE . '.index')->with('success', __(self::LANG_PATH . '_updated'));
        } catch (Throwable $e) {
            $this->LogError("Permission update failed", $e);
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(DeletePermissionRequest $request): RedirectResponse
    {
        try {
            $this->permissionService->delete($request->validated()['id']);
            return redirect()->route(self::ROUTE . '.index')->with('success', __(self::LANG_PATH . '_deleted'));
        } catch (WarningException $e) {
            return back()->with('error', __($e->getMessage()));
        } catch (Throwable $e) {
            $this->LogError("Permission destroy failed", $e);
            return back()->with('error', $e->getMessage());
        }
    }
}
