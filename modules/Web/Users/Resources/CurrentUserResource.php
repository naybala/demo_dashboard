<?php
namespace BasicDashboard\Web\Users\Resources;

use Illuminate\Support\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class CurrentUserResource extends JsonResource
{
    protected function formatLastActivity($timestamp): string|null
    {
        return $timestamp ? Carbon::parse($timestamp)->diffForHumans() : null;
    }
    public function toArray($request): array
    {
        return [
            "user_id"              => customEncoder($this->user_id),
            "user_fullname"        => $this->user_fullname,
            "user_email"           => $this->user_email,
            "last_used_at"         => $this->formatLastActivity($this->last_used_at),
        ];
    }
}
