<?php
namespace BasicDashboard\Web\Permissions\Validation;

use Illuminate\Foundation\Http\FormRequest;

class DeletePermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => ['required', 'string'],
        ];
    }
}
