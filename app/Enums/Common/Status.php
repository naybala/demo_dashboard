<?php

namespace App\Enums\Common;

use App\Traits\Enum\EnumCaseToArray;

enum Status : string
{
    use EnumCaseToArray;
    case Active = "active";
    case Inactive = "inactive";
    
    public function label() : string
    {
        return match($this){
            self::Active => "Active",
            self::Inactive => "Inactive",
        };
    }
}