<?php

namespace BasicDashboard\Web\Inventories\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InventoryResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            "id" => customEncoder($this->id),
            "warehouse_id" => customEncoder($this->warehouse_id),
            "warehouse_name" => $this->warehouse?->name ?? 'N/A',
            "own_product_id" => customEncoder($this->own_product_id),
            "own_product_name" => $this->ownProduct?->name ?? 'N/A',
            "unit" => $this->ownProduct?->unit?->name ?? '',
            "quantity" => (float) $this->quantity,
        ];
    }
}
