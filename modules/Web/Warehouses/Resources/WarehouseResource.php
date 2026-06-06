<?php

namespace BasicDashboard\Web\Warehouses\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WarehouseResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            "id" => customEncoder($this->id),
            "name" => $this->name,
            "location" => $this->location,
            "description" => $this->description,
        ];
    }
}
