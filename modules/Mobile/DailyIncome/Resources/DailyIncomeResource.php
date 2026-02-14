<?php

namespace BasicDashboard\Mobile\DailyIncome\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DailyIncomeResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'date' => $this->date,
            'amount' => $this->amount,
            'price' => $this->price,
            'investment' => $this->investment,
            'profit' => $this->profit,
            'own_product' => [
                'id' => $this->ownProduct?->id,
                'name' => $this->ownProduct?->name,
                'unit' => $this->ownProduct?->unit?->name,
            ],
            'voucher_no' => $this->dailyIncomeTotal?->voucher_no,
            'note' => $this->dailyIncomeTotal?->note,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
