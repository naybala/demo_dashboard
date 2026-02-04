<?php
namespace BasicDashboard\Web\Users\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserEditResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            "id"              => customEncoder($this->id),
            'fullname'        => $this->fullname,
            'user_type'       => $this->user_type,
            'user_type_label' => $this->user_type?->label(),
            "email"           => $this->email,
            'password'        => $this->password,
            'phone_number'    => $this->phone_number,
            "avatar"          => $this->avator,
            "status"          => $this->status,
            "status_text"     => $this->status->label(),
            "country_id"      => $this->country_id,
            "role_marked"     => $this->roles->value("id"),
        ];
    }
}
