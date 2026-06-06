<?php

namespace BasicDashboard\Web\Inventories\Controllers;

use BasicDashboard\Web\Common\BaseController;
use BasicDashboard\Web\Inventories\Services\InventoryService;
use BasicDashboard\Web\Inventories\Resources\InventoryResource;
use BasicDashboard\Web\Warehouses\Services\WarehouseService;
use BasicDashboard\Web\Warehouses\Resources\WarehouseResource;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class InventoryController extends BaseController
{
    public function __construct(
        private InventoryService $inventoryService,
        private WarehouseService $warehouseService
    ) {
    }

    public function index(Request $request): Response
    {
        $inventories = $this->inventoryService->paginate($request->all());
        $inventories = InventoryResource::collection($inventories)->response()->getData(true);
        
        $warehouses = $this->warehouseService->all();
        $warehouses = WarehouseResource::collection($warehouses)->response()->getData(true)['data'];

        return Inertia::render('Inventories/Index', array_merge($inventories, [
            'warehouses' => $warehouses,
            'filters' => $request->only(['keyword', 'warehouse_id'])
        ]));
    }

    public function checkStock(Request $request): \Illuminate\Http\JsonResponse
    {
        $warehouseId = $request->get('warehouse_id') ? customDecoder($request->get('warehouse_id')) : null;
        $ownProductId = $request->get('own_product_id') ? customDecoder($request->get('own_product_id')) : null;

        if (!$warehouseId || !$ownProductId) {
            return response()->json(['quantity' => 0]);
        }

        $qty = $this->inventoryService->getStockLevel((int)$warehouseId, (int)$ownProductId);

        return response()->json(['quantity' => $qty]);
    }
}
