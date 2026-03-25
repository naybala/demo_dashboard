<?php

namespace BasicDashboard\Web\AcademicSessions\Services;

use BasicDashboard\Foundations\Domain\AcademicSessions\AcademicSession;
use Illuminate\Support\Facades\Auth;

class AcademicSessionService
{
    public function __construct(
        private AcademicSession $academicSession
    ) {}

    public function paginate(array $request)
    {
        return $this->academicSession
            ->filterByKeyword($request['keyword'] ?? null)
            ->orderByLatest()
            ->paginate($request['paginate'] ?? config('numbers.paginate'));
    }

    public function store(array $request): AcademicSession
    {
        $request['created_by'] = Auth::id();
        return $this->academicSession->create($request);
    }

    public function findOrFail(int $id): AcademicSession
    {
        return $this->academicSession->findOrFail($id);
    }

    public function update(array $request, string $id): AcademicSession
    {
        $decodedId = customDecoder($id);
        $academicSession = $this->academicSession->findOrFail($decodedId);
        $request['updated_by'] = Auth::id();
        $academicSession->update($request);
        return $academicSession;
    }

    public function delete(string $id): void
    {
        $decodedId = customDecoder($id);
        $academicSession = $this->academicSession->findOrFail($decodedId);
        $academicSession->delete();
    }
}
