<?php

namespace App\Rules\Web;

use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Validation\Rule;

class NoChildRecords implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(
        protected string $tableName,
        protected string $columnName,
        protected string $message
    )
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $relatedCount = DB::table($this->tableName)->where($this->columnName, $value)->count();
        return $relatedCount === 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
