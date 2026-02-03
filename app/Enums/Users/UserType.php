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
            self::User => "User",
        };
    }
}