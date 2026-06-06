<?php

namespace BasicDashboard\Foundations\Domain\Warehouses;

use App\Observers\AuditObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use BasicDashboard\Foundations\Domain\Inventories\Inventory;
use BasicDashboard\Foundations\Domain\StockTransactions\StockTransaction;

#[ObservedBy([AuditObserver::class])]
#[Fillable([
    'name',
    'location',
    'description',
    'created_by',
    'updated_by',
    'deleted_by',
])]
class Warehouse extends Model
{
    use HasFactory, SoftDeletes;

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    public function stockTransactions()
    {
        return $this->hasMany(StockTransaction::class);
    }

    /**
     * Scope to filter by keyword search
     */
    public function scopeFilterByKeyword(Builder $query, ?string $keyword): Builder
    {
        return $query->when($keyword, function (Builder $q) use ($keyword) {
            $q->where('name', 'LIKE', '%' . $keyword . '%')
              ->orWhere('location', 'LIKE', '%' . $keyword . '%');
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
