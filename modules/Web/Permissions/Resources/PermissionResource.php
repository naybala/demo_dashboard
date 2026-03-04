<?php
namespace BasicDashboard\Web\Permissions\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'         => customEncoder($this->id),
            'name'       => $this->name,
            'guard_name' => $this->guard_name,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
