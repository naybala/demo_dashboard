<?php

namespace App\Enums\Users;

use App\Traits\Enum\EnumCaseToArray;

enum Gender : int
{
    use EnumCaseToArray;
    case Male = 1;
    case Female = 2;

    case Other = 3;
    
    public function label() : string
    {
        return match($this){
            self::Male => "Male",
            self::Female => "Female",
            self::Other => "Other",
        };
    }

    public static function options(): array
    {
        return array_map(fn($case) => [
            'value' => $case->value,
            'label' => $case->label(),
        ], self::cases());
    }
}