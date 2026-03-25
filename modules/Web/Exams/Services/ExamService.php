<?php

namespace BasicDashboard\Web\Exams\Services;

use BasicDashboard\Foundations\Domain\Exams\Exam;
use Illuminate\Support\Facades\Auth;

class ExamService
{
    public function __construct(
        private Exam $exam
    ) {}

    public function paginate(array $request)
    {
        return $this->exam
            ->with(['academicSession'])
            ->filterByKeyword($request['keyword'] ?? null)
            ->orderByLatest()
            ->paginate($request['paginate'] ?? config('numbers.paginate'));
    }

    public function store(array $request): Exam
    {
        $request['created_by'] = Auth::id();
        return $this->exam->create($request);
    }

    public function findOrFail(int $id): Exam
    {
        return $this->exam->findOrFail($id);
    }

    public function update(array $request, string $id): Exam
    {
        $decodedId = customDecoder($id);
        $exam = $this->exam->findOrFail($decodedId);
        $request['updated_by'] = Auth::id();
        $exam->update($request);
        return $exam;
    }

    public function delete(string $id): void
    {
        $decodedId = customDecoder($id);
        $exam = $this->exam->findOrFail($decodedId);
        $exam->delete();
    }
}
