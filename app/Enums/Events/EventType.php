<?php

namespace App\Enums\Events;

enum EventType: string
{
    case MEETING = 'meeting';
    case SPORT = 'sport';
    case ACADEMIC = 'academic';
    case CULTURE = 'culture';

    public function label(): string
    {
        return match($this) {
            self::MEETING => 'Meeting',
            self::SPORT => 'Sport',
            self::ACADEMIC => 'Academic',
            self::CULTURE => 'Culture',
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
