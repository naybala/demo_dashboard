<?php

namespace BasicDashboard\Web\StockTransactions\Validation;

use Illuminate\Foundation\Http\FormRequest;

class StoreStockTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "warehouse_id" => "required",
            "own_product_id" => "required",
            "quantity" => "required|numeric|min:0.01",
            "type" => "required|in:in,out",
            "note" => "nullable|string|max:255",
        ];
    }
}
