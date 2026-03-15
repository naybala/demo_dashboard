<?php

namespace BasicDashboard\Web\DailyIncomes\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DailyIncomesSampleExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        return [
            [
                '2023-10-25',
                'Example Product A',
                '10',
                '1',
                'Example note here'
            ],
            [
                '2023-10-25',
                'Example Product B',
                '5',
                '1',
                'Example note here'
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'Date',
            'Product Name',
            'Amount',
            'Is Instant (1 or 0)',
            'Note'
        ];
    }
}
