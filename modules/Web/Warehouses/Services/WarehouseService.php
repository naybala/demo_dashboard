<?php

namespace BasicDashboard\Web\Warehouses\Services;

use BasicDashboard\Foundations\Domain\Warehouses\Warehouse;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Exceptions\WarningException;

class WarehouseService
{
    public function __construct(
        private Warehouse $warehouse
    ) {
    }

    public function all()
    {
        return $this->warehouse->orderBy('name')->get();
    }

    public function paginate(array $request): LengthAwarePaginator
    {
        return $this->warehouse
            ->filterByKeyword($request['keyword'] ?? null)
            ->orderByLatest()
            ->paginate($request['paginate'] ?? config('numbers.paginate'));
    }

    public function store(array $request): void
    {
        DB::transaction(function () use ($request) {
            $this->warehouse->create([
                ...$request,
                'created_by' => auth()->id(),
            ]);
        });
    }

    public function findOrFail(string $id): Warehouse
    {
        $decodedId = customDecoder($id);
        return $this->warehouse->findOrFail($decodedId);
    }

    public function update(array $request, string $id): void
    {
        DB::transaction(function () use ($request, $id) {
            $decodedId = customDecoder($id);
            $warehouse = $this->warehouse->findOrFail($decodedId);
            $warehouse->update([
                ...$request,
                'updated_by' => auth()->id(),
            ]);
        });
    }

    public function delete(string $id): void
    {
        DB::transaction(function () use ($id) {
            $decodedId = customDecoder($id);
            $warehouse = $this->warehouse->findOrFail($decodedId);
            if ($warehouse->inventories()->where('quantity', '>', 0)->exists() || $warehouse->stockTransactions()->exists()) {
                throw new WarningException('warehouse.warehouse_in_use');
            }
            $warehouse->update([
                'deleted_by' => auth()->id(),
            ]);
            $warehouse->delete();
        });
    }
}
