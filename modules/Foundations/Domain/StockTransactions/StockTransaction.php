<?php

namespace BasicDashboard\Foundations\Domain\StockTransactions;

use App\Observers\AuditObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use BasicDashboard\Foundations\Domain\Warehouses\Warehouse;
use BasicDashboard\Foundations\Domain\OwnProducts\OwnProduct;
use Illuminate\Database\Eloquent\Builder;

#[ObservedBy([AuditObserver::class])]
#[Fillable([
    'warehouse_id',
    'own_product_id',
    'quantity',
    'type',
    'reference_type',
    'reference_id',
    'note',
    'created_by',
    'updated_by',
    'deleted_by',
])]
class StockTransaction extends Model
{
    use HasFactory, SoftDeletes;

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function ownProduct()
    {
        return $this->belongsTo(OwnProduct::class);
    }

    /**
     * Scope to filter by keyword (product name or note)
     */
    public function scopeFilterByKeyword(Builder $query, ?string $keyword): Builder
    {
        return $query->when($keyword, function (Builder $q) use ($keyword) {
            $q->where(function ($sub) use ($keyword) {
                $sub->where('note', 'LIKE', '%' . $keyword . '%')
                    ->orWhereHas('ownProduct', function ($p) use ($keyword) {
                        $p->where('name', 'LIKE', '%' . $keyword . '%');
                    });
            });
        });
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
     * Scope to filter by type
     */
    public function scopeFilterByType(Builder $query, ?string $type): Builder
    {
        return $query->when($type, function (Builder $q) use ($type) {
            $q->where('type', $type);
        });
    }

    /**
     * Scope to order by latest activity
     */
    public function scopeOrderByLatest(Builder $query): Builder
    {
        return $query->orderByRaw('CASE WHEN created_at IS NULL THEN updated_at ELSE created_at END DESC')
            ->orderByDesc('id');
    }
}
