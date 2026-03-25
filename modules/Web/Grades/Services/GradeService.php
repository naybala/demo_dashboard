<?php

namespace BasicDashboard\Web\Grades\Services;

use BasicDashboard\Foundations\Domain\Grades\Grade;
use Illuminate\Support\Facades\Auth;

class GradeService
{
    public function __construct(
        private Grade $grade
    ) {}

    public function paginate(array $request)
    {
        return $this->grade
            ->filterByKeyword($request['keyword'] ?? null)
            ->orderByLatest()
            ->paginate($request['paginate'] ?? config('numbers.paginate'));
    }

    public function store(array $request): Grade
    {
        $request['created_by'] = Auth::id();
        return $this->grade->create($request);
    }

    public function findOrFail(int $id): Grade
    {
        return $this->grade->findOrFail($id);
    }

    public function update(array $request, string $id): Grade
    {
        $decodedId = customDecoder($id);
        $grade = $this->grade->findOrFail($decodedId);
        $request['updated_by'] = Auth::id();
        $grade->update($request);
        return $grade;
    }

    public function delete(string $id): void
    {
        $decodedId = customDecoder($id);
        $grade = $this->grade->findOrFail($decodedId);
        $grade->delete();
    }
}
