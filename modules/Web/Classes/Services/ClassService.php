<?php

namespace BasicDashboard\Web\Classes\Services;

use BasicDashboard\Foundations\Domain\Classes\SchoolClass;
use Illuminate\Support\Facades\Auth;

class ClassService
{
    public function __construct(
        private SchoolClass $schoolClass
    ) {}

    public function paginate(array $request)
    {
        return $this->schoolClass
            ->with(['grade'])
            ->filterByKeyword($request['keyword'] ?? null)
            ->orderByLatest()
            ->paginate($request['paginate'] ?? config('numbers.paginate'));
    }

    public function store(array $request): SchoolClass
    {
        $request['created_by'] = Auth::id();
        return $this->schoolClass->create($request);
    }

    public function findOrFail(int $id): SchoolClass
    {
        return $this->schoolClass->findOrFail($id);
    }

    public function update(array $request, string $id): SchoolClass
    {
        $decodedId = customDecoder($id);
        $schoolClass = $this->schoolClass->findOrFail($decodedId);
        $request['updated_by'] = Auth::id();
        $schoolClass->update($request);
        return $schoolClass;
    }

    public function delete(string $id): void
    {
        $decodedId = customDecoder($id);
        $schoolClass = $this->schoolClass->findOrFail($decodedId);
        $schoolClass->delete();
    }
}
