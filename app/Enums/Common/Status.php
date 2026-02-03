<?php

namespace App\Enums\Common;

use App\Traits\Enum\EnumCaseToArray;

enum Status : int
{
    use EnumCaseToArray;
    case Active = 1;
    case Inactive = 0;
    
    public function label() : string
    {
        return match($this){
            self::Active => "Active",
            self::Inactive => "Inactive",
        };
    }
}