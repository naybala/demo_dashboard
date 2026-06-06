<?php

namespace BasicDashboard\Foundations\Domain\Inventories;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use BasicDashboard\Foundations\Domain\Warehouses\Warehouse;
use BasicDashboard\Foundations\Domain\OwnProducts\OwnProduct;
use Illuminate\Database\Eloquent\Builder;

#[Fillable([
    'warehouse_id',
    'own_product_id',
    'quantity',
])]
class Inventory extends Model
{
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function ownProduct()
    {
        return $this->belongsTo(OwnProduct::class);
    }

    /**
     * Scope to filter by warehouse
     */
    public function scopeFilterByWarehouse(Builder $query, ?int $warehouseId): Builder
    {
        return $query->when($warehouseId, function (Builder $q) use ($warehouseId) {
            $q->where('warehouse_id', $warehouseId);
        });
    }

    /**
     * Scope to filter by keyword (product name)
     */
    public function scopeFilterByKeyword(Builder $query, ?string $keyword): Builder
    {
        return $query->when($keyword, function (Builder $q) use ($keyword) {
            $q->whereHas('ownProduct', function ($sub) use ($keyword) {
                $sub->where('name', 'LIKE', '%' . $keyword . '%');
            });
        });
    }
}
