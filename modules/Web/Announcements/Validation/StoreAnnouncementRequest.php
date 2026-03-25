<?php

namespace BasicDashboard\Web\Announcements\Validation;

use App\Enums\Announcements\AnnouncementDepartment;
use App\Enums\Announcements\AnnouncementDestination;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreAnnouncementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'department'  => ['required', new Enum(AnnouncementDepartment::class)],
            'date'        => ['required', 'date'],
            'destination' => ['required', new Enum(AnnouncementDestination::class)],
        ];
    }
}
