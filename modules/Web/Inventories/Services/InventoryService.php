<?php

namespace BasicDashboard\Web\Inventories\Services;

use BasicDashboard\Foundations\Domain\Inventories\Inventory;
use Illuminate\Pagination\LengthAwarePaginator;

class InventoryService
{
    public function __construct(
        private Inventory $inventory
    ) {
    }

    public function paginate(array $request): LengthAwarePaginator
    {
        return $this->inventory
            ->with(['warehouse', 'ownProduct.unit'])
            ->filterByWarehouse($request['warehouse_id'] ?? null)
            ->filterByKeyword($request['keyword'] ?? null)
            ->paginate($request['paginate'] ?? config('numbers.paginate'));
    }

    public function getStockLevel(int $warehouseId, int $ownProductId): float
    {
        $inv = $this->inventory->where('warehouse_id', $warehouseId)
            ->where('own_product_id', $ownProductId)
            ->first();

        return $inv ? (float) $inv->quantity : 0.00;
    }

    public function hasSufficientStock(int $warehouseId, int $ownProductId, float $requestedQty): bool
    {
        return $this->getStockLevel($warehouseId, $ownProductId) >= $requestedQty;
    }

    public function adjustInventory(int $warehouseId, int $ownProductId, float $qtyChange): void
    {
        $inv = $this->inventory->firstOrCreate(
            [
                'warehouse_id' => $warehouseId,
                'own_product_id' => $ownProductId,
            ],
            [
                'quantity' => 0.00,
            ]
        );

        $inv->quantity += $qtyChange;
        $inv->save();
    }
}
