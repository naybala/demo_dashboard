<?php
namespace BasicDashboard\Web\Permissions\Validation;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->permission ? $this->permission->id : null;
        return [
            'name' => ['required', 'string', 'max:255', 'unique:permissions,name,' . $id],
        ];
    }
}
