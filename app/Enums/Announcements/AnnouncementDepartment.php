<?php

namespace App\Enums\Announcements;

use App\Traits\Enum\EnumCaseToArray;

enum AnnouncementDepartment: string
{
    use EnumCaseToArray;

    case AdminOffice = 'Admin Office';
    case HeadOffice = 'Head Office';
    case AcademicOffice = 'Academic Office';

    public function label(): string
    {
        return match ($this) {
            self::AdminOffice => 'Admin Office',
            self::HeadOffice => 'Head Office',
            self::AcademicOffice => 'Academic Office',
        };
    }
}
