<?php

namespace BasicDashboard\Web\Events\Services;

use BasicDashboard\Foundations\Domain\Events\Event;
use Illuminate\Support\Facades\DB;

class EventService
{
    public function getPaginatedEvents(array $filters)
    {
        return Event::query()
            ->filterByKeyword($filters['keyword'] ?? null)
            ->when($filters['start_date'] ?? null, function ($query, $startDate) {
                return $query->whereDate('date', '>=', $startDate);
            })
            ->when($filters['end_date'] ?? null, function ($query, $endDate) {
                return $query->whereDate('date', '<=', $endDate);
            })
            ->orderByLatest()
            ->paginate(10);
    }

    public function createEvent(array $data)
    {
        return DB::transaction(function () use ($data) {
            return Event::create($data);
        });
    }

    public function updateEvent(Event $event, array $data)
    {
        return DB::transaction(function () use ($event, $data) {
            $event->update($data);
            return $event;
        });
    }

    public function deleteEvent(Event $event)
    {
        return DB::transaction(function () use ($event) {
            return $event->delete();
        });
    }
}
