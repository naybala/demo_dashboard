<?php

namespace BasicDashboard\Web\DailyIncomes\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use BasicDashboard\Foundations\Domain\OwnProducts\OwnProduct;
use BasicDashboard\Web\DailyIncomes\Services\DailyIncomeService;
use Exception;

class DailyIncomesImport implements ToCollection, WithHeadingRow
{
    protected DailyIncomeService $dailyIncomeService;

    public function __construct(DailyIncomeService $dailyIncomeService)
    {
        $this->dailyIncomeService = $dailyIncomeService;
    }

    public function collection(Collection $rows)
    {
        $vouchers = [];

        foreach ($rows as $index => $row) {
            // Check if row is empty (sometimes Excel leaves blank rows)
            if (!isset($row['date']) && !isset($row['product_name'])) {
                continue;
            }

            $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['date'])->format('Y-m-d');
            $productName = $row['product_name'];

            $product = OwnProduct::where('name', $productName)->first();

            if (!$product) {
                throw new Exception("Product '{$productName}' not found in row " . ($index + 2));
            }

            // Group by Date, Is Instant, and Note
            $isInstant = isset($row['is_instant']) ? (bool) $row['is_instant'] : true;
            $note = $row['note'] ?? null;
            $groupKey = $date . '_' . ($isInstant ? '1' : '0') . '_' . $note;

            if (!isset($vouchers[$groupKey])) {
                $vouchers[$groupKey] = [
                    'date' => $date,
                    'is_instant' => $isInstant,
                    'note' => $note,
                    'items' => []
                ];
            }

            $vouchers[$groupKey]['items'][] = [
                'own_product_id' => $product->id,
                'amount' => $row['amount'],
                'price' => $product->price ?? 0,
                'investment' => $product->investment ?? 0,
                'profit' => $product->profit ?? 0,
            ];
        }

        foreach ($vouchers as $voucherNo => $voucherData) {
            $this->dailyIncomeService->store($voucherData);
        }
    }
}
