<?php

namespace BasicDashboard\Foundations\Domain\DailyIncomeTotals;

use App\Observers\AuditObserver;
use BasicDashboard\Foundations\Domain\DailyIncomes\DailyIncome;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[ObservedBy([AuditObserver::class])]
class DailyIncomeTotal extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'daily_income_totals';

    protected $fillable = [
        'voucher_no',
        'total_price',
        'total_investment',
        'total_profit',
        'note',
        'is_instant',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    public function dailyIncomes()
    {
        return $this->hasMany(DailyIncome::class);
    }
}
