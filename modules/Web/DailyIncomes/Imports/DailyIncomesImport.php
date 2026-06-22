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
    protected ?string $warehouseId;

    public function __construct(DailyIncomeService $dailyIncomeService, ?string $warehouseId = null)
    {
        $this->dailyIncomeService = $dailyIncomeService;
        $this->warehouseId = $warehouseId;
    }

    public function collection(Collection $rows)
    {
        $vouchers = [];

        foreach ($rows as $index => $row) {
            // Check if row is empty (sometimes Excel leaves blank rows)
            if (!isset($row['date']) && !isset($row['product_name'])) {
                continue;
            }

            $rawDate = $row['date'];
            if (is_numeric($rawDate)) {
                $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($rawDate)->format('Y-m-d');
            } else {
                try {
                    $date = \Carbon\Carbon::parse($rawDate)->format('Y-m-d');
                } catch (\Exception $e) {
                    throw new Exception("Invalid date format in row " . ($index + 2) . ": " . $rawDate);
                }
            }
            $productName = $row['product_name'];

            $product = OwnProduct::where('name', $productName)->first();


            if (!$product) {
                throw new Exception("Product '{$productName}' not found in row " . ($index + 2));
            }

            // Group by Date, Is Instant, and Note
            $rawIsInstant = $row['is_instant_1_or_0'] ?? $row['is_instant'] ?? null;
            $isInstant = $rawIsInstant !== null && trim((string)$rawIsInstant) !== '' ? (bool) $rawIsInstant : true;
            $note = $row['note'] ?? null;
            
            // Generate a grouping key based on the row attributes that define a unique voucher header
            // Rows with the exact same Date, Is Instant, and Note will be grouped together
            $groupKey = $date . '_' . ($isInstant ? '1' : '0') . '_' . $note;

            if (!isset($vouchers[$groupKey])) {
                $vouchers[$groupKey] = [
                    'date' => $date,
                    'is_instant' => $isInstant,
                    'note' => $note,
                    'warehouse_id' => $this->warehouseId,
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
