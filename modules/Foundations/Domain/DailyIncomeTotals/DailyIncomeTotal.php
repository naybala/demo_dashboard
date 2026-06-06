<?php

namespace BasicDashboard\Foundations\Domain\DailyIncomeTotals;

use App\Observers\AuditObserver;
use BasicDashboard\Foundations\Domain\DailyIncomes\DailyIncome;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[ObservedBy([AuditObserver::class])]
#[Fillable([
    'warehouse_id',
    'voucher_no',
    'total_price',
    'total_investment',
    'total_profit',
    'note',
    'is_instant',
    'created_by',
    'updated_by',
    'deleted_by',
])]
class DailyIncomeTotal extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'daily_income_totals';


    public function dailyIncomes(): HasMany
    {
        return $this->hasMany(DailyIncome::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(\BasicDashboard\Foundations\Domain\Warehouses\Warehouse::class);
    }
}
