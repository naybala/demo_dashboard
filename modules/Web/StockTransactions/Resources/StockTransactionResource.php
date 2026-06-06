<?php

namespace BasicDashboard\Web\StockTransactions\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StockTransactionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            "id" => customEncoder($this->id),
            "warehouse_name" => $this->warehouse?->name ?? 'N/A',
            "own_product_name" => $this->ownProduct?->name ?? 'N/A',
            "unit" => $this->ownProduct?->unit?->name ?? '',
            "quantity" => (float) $this->quantity,
            "type" => $this->type,
            "reference_type" => $this->reference_type,
            "note" => $this->note,
            "created_at" => $this->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
