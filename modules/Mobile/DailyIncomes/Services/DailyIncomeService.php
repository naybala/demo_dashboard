<?php

namespace BasicDashboard\Mobile\DailyIncomes\Services;

use BasicDashboard\Foundations\Domain\DailyIncomes\DailyIncome;
use BasicDashboard\Foundations\Domain\DailyIncomeTotals\DailyIncomeTotal;
use BasicDashboard\Web\DailyIncomes\Actions\DailyIncomeAction;
use Illuminate\Support\Facades\DB;

class DailyIncomeService
{
    public function __construct(
        private DailyIncome $dailyIncome,
        private DailyIncomeTotal $dailyIncomeTotal,
        private DailyIncomeAction $dailyIncomeAction
    ) {
    }

    public function paginate(array $request)
    {
        return $this->dailyIncome
            ->with(['ownProduct.unit', 'dailyIncomeTotal'])
            ->filterByKeyword($request['keyword'] ?? null)
            ->filterByDateRange($request['from_date'] ?? null, $request['to_date'] ?? null)
            ->orderByLatest()
            ->paginate($request['paginate'] ?? config('numbers.paginate'));
    }

    public function store(array $request): void
    {
        DB::transaction(function () use ($request) {
            $totals = $this->dailyIncomeAction->calculateTotalsAndRows($request);
            $total = $this->dailyIncomeAction->createTotal($request, $totals, $this->dailyIncomeTotal);
            $this->dailyIncomeAction->createDailyIncomes($totals['rows'], $total->id, $this->dailyIncome);
        });
    }

    public function findOrFail(string $id): DailyIncome
    {
        return $this->dailyIncome->with(['ownProduct.unit', 'dailyIncomeTotal'])->findOrFail($id);
    }

    public function getByVoucherNo(DailyIncome $dailyIncome)
    {
        if (!$dailyIncome->daily_income_total_id) {
            return collect([$dailyIncome]);
        }
        $items = $this->dailyIncome->where('daily_income_total_id', $dailyIncome->daily_income_total_id)
            ->with(['ownProduct.unit', 'dailyIncomeTotal'])
            ->get();
        return $items->isEmpty() ? collect([$dailyIncome]) : $items;
    }


    public function update(array $request, string $id): void
    {
        DB::transaction(function () use ($request, $id) {
            $totalId = $this->dailyIncomeAction->getTotalIdFromIncome($id,$this->dailyIncome);
            $totals = $this->dailyIncomeAction->calculateTotalsAndRows($request);
            $this->dailyIncomeAction->updateTotal($totalId, $request, $totals,$this->dailyIncomeTotal);
            $this->dailyIncomeAction->replaceDailyIncomes($totalId, $totals['rows'],$this->dailyIncome);
        });
    }
}
