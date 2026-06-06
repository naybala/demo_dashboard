<?php

namespace BasicDashboard\Web\StockTransactions\Services;

use BasicDashboard\Foundations\Domain\StockTransactions\StockTransaction;
use BasicDashboard\Web\Inventories\Services\InventoryService;
use App\Exceptions\WarningException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class StockTransactionService
{
    public function __construct(
        private StockTransaction $stockTransaction,
        private InventoryService $inventoryService
    ) {
    }

    public function paginate(array $request): LengthAwarePaginator
    {
        return $this->stockTransaction
            ->with(['warehouse', 'ownProduct.unit'])
            ->filterByWarehouse($request['warehouse_id'] ?? null)
            ->filterByType($request['type'] ?? null)
            ->filterByKeyword($request['keyword'] ?? null)
            ->orderByLatest()
            ->paginate($request['paginate'] ?? config('numbers.paginate'));
    }

    public function store(array $request): void
    {
        DB::transaction(function () use ($request) {
            $warehouseId = (int) customDecoder($request['warehouse_id']);
            $ownProductId = (int) customDecoder($request['own_product_id']);
            $quantity = (float) $request['quantity'];
            $type = $request['type'];

            if ($type === 'out') {
                if (!$this->inventoryService->hasSufficientStock($warehouseId, $ownProductId, $quantity)) {
                    throw new WarningException('inventory.insufficient_stock');
                }
            }

            // Create transaction
            $this->stockTransaction->create([
                'warehouse_id' => $warehouseId,
                'own_product_id' => $ownProductId,
                'quantity' => $quantity,
                'type' => $type,
                'reference_type' => 'manual',
                'note' => $request['note'] ?? null,
                'created_by' => auth()->id(),
            ]);

            // Adjust inventory balance
            $qtyChange = $type === 'in' ? $quantity : -$quantity;
            $this->inventoryService->adjustInventory($warehouseId, $ownProductId, $qtyChange);
        });
    }
}
