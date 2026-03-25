<?php

namespace App\Enums\Users;

use App\Traits\Enum\EnumCaseToArray;

enum UserType : int
{
    use EnumCaseToArray;
    case Administrator = 1;
    case User = 2;
    
    public function label() : string
    {
        return match($this){
            self::Administrator => "Administrator",
            self::User => "Teacher",
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