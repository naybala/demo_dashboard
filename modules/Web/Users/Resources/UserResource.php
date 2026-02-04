<?php
namespace BasicDashboard\Web\Users\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserResource extends JsonResource
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
            'phone_number'    => $this->phone_number,
            "email"           => $this->email,
            'password'        => $this->password,
            "avatar"          => $avatar,
            "status"          => $this->status,
            "status_text"     => $this->status->label(),
            'role_marked'     => $this->roles->value('name'),
            'created_at'      => $this->created_at ? Carbon::parse($this->created_at)->format('d/F/Y') : '---',
        ];
    }
}
