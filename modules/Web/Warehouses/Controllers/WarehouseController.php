<?php

namespace BasicDashboard\Web\Warehouses\Controllers;

use BasicDashboard\Web\Warehouses\Resources\WarehouseResource;
use BasicDashboard\Web\Common\BaseController;
use BasicDashboard\Web\Warehouses\Services\WarehouseService;
use BasicDashboard\Web\Warehouses\Validation\StoreWarehouseRequest;
use BasicDashboard\Web\Warehouses\Validation\UpdateWarehouseRequest;
use BasicDashboard\Web\Warehouses\Validation\DeleteWarehouseRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Exceptions\WarningException;
use Throwable;
use Inertia\Inertia;
use Inertia\Response;

class WarehouseController extends BaseController
{
    const VIEW = 'Warehouses/Index';
    const ROUTE = 'warehouses';
    const LANG_PATH = "warehouse.warehouse";

    public function __construct(
        private WarehouseService $warehouseService
    ) {
    }

    public function index(Request $request): Response
    {
        $warehouseList = $this->warehouseService->paginate($request->all() ?? []);
        $warehouseList = WarehouseResource::collection($warehouseList)->response()->getData(true);
        return Inertia::render(self::VIEW, $warehouseList);
    }

    public function store(StoreWarehouseRequest $request): RedirectResponse
    {
        try {
            $this->warehouseService->store($request->validated());
            return redirect()->route(self::ROUTE . '.index')->with('success', __(self::LANG_PATH . '_created'));
        } catch (Throwable $e) {
            $this->LogError("Warehouse store failed", $e);
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(UpdateWarehouseRequest $request, string $id): RedirectResponse
    {
        try {
            $this->warehouseService->update($request->validated(), $id);
            return redirect()->route(self::ROUTE . '.index')->with('success', __(self::LANG_PATH . '_updated'));
        } catch (Throwable $e) {
            $this->LogError("Warehouse update failed", $e);
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(DeleteWarehouseRequest $request): RedirectResponse
    {
        try {
            $this->warehouseService->delete($request->validated()['id']);
            return redirect()->route(self::ROUTE . '.index')->with('success', __(self::LANG_PATH . '_deleted'));
        } catch (WarningException $e) {
            return back()->with('error', __($e->getMessage()));
        } catch (Throwable $e) {
            $this->LogError("Warehouse destroy failed", $e);
            return back()->with('error', $e->getMessage());
        }
    }

    public function search(Request $request): \Illuminate\Http\JsonResponse
    {
        $warehouses = $this->warehouseService->paginate([
            'keyword' => $request->get('keyword'),
            'paginate' => 20,
        ]);
        
        $warehouses = WarehouseResource::collection($warehouses);
        
        return response()->json($warehouses->response()->getData(true));
    }
}
