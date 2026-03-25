<?php

namespace App\Enums\Announcements;

use App\Traits\Enum\EnumCaseToArray;

enum AnnouncementDestination: string
{
    use EnumCaseToArray;

    case Student = 'student';
    case Teacher = 'teacher';
    case All = 'all';

    public function label(): string
    {
        return match ($this) {
            self::Student => 'Student',
            self::Teacher => 'Teacher',
            self::All => 'All',
        };
    }
}
