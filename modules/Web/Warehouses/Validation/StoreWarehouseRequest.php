<?php

namespace BasicDashboard\Web\Warehouses\Validation;

use Illuminate\Foundation\Http\FormRequest;

class StoreWarehouseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "name" => "required|string|max:255",
            "location" => "nullable|string|max:255",
            "description" => "nullable|string",
        ];
    }
}
