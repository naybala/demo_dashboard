<?php

namespace BasicDashboard\Web\Announcements\Services;

use BasicDashboard\Foundations\Domain\Announcements\Announcement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AnnouncementService
{
    public function __construct(
        private Announcement $announcement
    ) {}

    public function paginate(array $request)
    {
        return $this->announcement
            ->filterByKeyword($request['keyword'] ?? null)
            ->when($request['start_date'] ?? null, function ($query, $startDate) {
                return $query->where('date', '>=', $startDate);
            })
            ->when($request['end_date'] ?? null, function ($query, $endDate) {
                return $query->where('date', '<=', $endDate);
            })
            ->orderByLatest()
            ->paginate($request['paginate'] ?? config('numbers.paginate'));
    }

    public function store(array $request): Announcement
    {
        return DB::transaction(function () use ($request) {
            $request['created_by'] = Auth::id();
            return $this->announcement->create($request);
        });
    }

    public function findOrFail(int $id): Announcement
    {
        return $this->announcement->findOrFail($id);
    }

    public function update(array $request, string $id): Announcement
    {
        return DB::transaction(function () use ($request, $id) {
            $decodedId = customDecoder($id);
            $announcement = $this->announcement->findOrFail($decodedId);
            $request['updated_by'] = Auth::id();
            $announcement->update($request);
            
            return $announcement;
        });
    }

    public function delete(string $id): void
    {
        $decodedId = customDecoder($id);
        $announcement = $this->announcement->findOrFail($decodedId);
        $announcement->delete();
    }
}
