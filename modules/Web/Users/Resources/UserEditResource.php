<?php
namespace BasicDashboard\Web\Users\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserEditResource extends JsonResource
{
    public function toArray($request): array
    {
        $avatarCloudPhoto = $this->avatar ?: config('cache.default_profile_photo_cloud');
        $avatarLocalPhoto = $this->avatar ?: config('cache.default_profile_photo_local');
        $avatar = config('cache.file_system_disk') == 'local' ? $avatarLocalPhoto : Storage::url($avatarCloudPhoto);
        return [
            "id"              => customEncoder($this->id),
            'fullname'        => $this->fullname,
            'user_type'       => $this->user_type,
            'user_type_label' => $this->user_type?->label(),
            "email"           => $this->email,
            'password'        => $this->password,
            'phone_number'    => $this->phone_number,
            "avatar"          => $avatar,
            "status"          => $this->status,
            "status_text"     => $this->status->label(),
            "country_id"      => $this->country_id,
            "role_marked"     => $this->roles->value("id"),
        ];
    }
}
