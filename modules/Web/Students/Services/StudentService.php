<?php

namespace BasicDashboard\Web\Students\Services;

use BasicDashboard\Foundations\Domain\Students\Student;
use Illuminate\Support\Facades\Auth;

class StudentService
{
    public function __construct(
        private Student $student
    ) {}

    public function paginate(array $request)
    {
        return $this->student
            ->filterByKeyword($request['keyword'] ?? null)
            ->orderByLatest()
            ->paginate($request['paginate'] ?? config('numbers.paginate'));
    }

    public function store(array $request): Student
    {
        $request['created_by'] = Auth::id();
        return $this->student->create($request);
    }

    public function findOrFail(int $id): Student
    {
        return $this->student->findOrFail($id);
    }

    public function update(array $request, string $id): Student
    {
        $decodedId = customDecoder($id);
        $student = $this->student->findOrFail($decodedId);
        $request['updated_by'] = Auth::id();
        $student->update($request);
        return $student;
    }

    public function delete(string $id): void
    {
        $decodedId = customDecoder($id);
        $student = $this->student->findOrFail($decodedId);
        $student->delete();
    }
}
