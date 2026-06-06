<?php

namespace BasicDashboard\Web\Warehouses\Validation;

use Illuminate\Foundation\Http\FormRequest;

class DeleteWarehouseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'id' => $this->route('warehouse'),
        ]);
    }

    public function rules(): array
    {
        return [
            "id" => "required",
        ];
    }
}
