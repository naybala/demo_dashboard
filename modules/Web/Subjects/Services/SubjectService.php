<?php

namespace BasicDashboard\Web\Subjects\Services;

use BasicDashboard\Foundations\Domain\Subjects\Subject;
use Illuminate\Support\Facades\Auth;

class SubjectService
{
    public function __construct(
        private Subject $subject
    ) {}

    public function paginate(array $request)
    {
        return $this->subject
            ->filterByKeyword($request['keyword'] ?? null)
            ->orderByLatest()
            ->paginate($request['paginate'] ?? config('numbers.paginate'));
    }

    public function store(array $request): Subject
    {
        $request['created_by'] = Auth::id();
        return $this->subject->create($request);
    }

    public function findOrFail(int $id): Subject
    {
        return $this->subject->findOrFail($id);
    }

    public function update(array $request, string $id): Subject
    {
        $decodedId = customDecoder($id);
        $subject = $this->subject->findOrFail($decodedId);
        $request['updated_by'] = Auth::id();
        $subject->update($request);
        return $subject;
    }

    public function delete(string $id): void
    {
        $decodedId = customDecoder($id);
        $subject = $this->subject->findOrFail($decodedId);
        $subject->delete();
    }
}
