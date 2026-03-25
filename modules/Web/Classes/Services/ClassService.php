<?php

namespace BasicDashboard\Web\Classes\Services;

use BasicDashboard\Foundations\Domain\Classes\SchoolClass;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClassService
{
    public function __construct(
        private SchoolClass $schoolClass
    ) {}

    public function paginate(array $request)
    {
        return $this->schoolClass
            ->with(['grade', 'session', 'headTeacher', 'subjects'])
            ->filterByKeyword($request['keyword'] ?? null)
            ->orderByLatest()
            ->paginate($request['paginate'] ?? config('numbers.paginate'));
    }

    public function store(array $request): SchoolClass
    {
        return DB::transaction(function () use ($request) {
            $request['created_by'] = Auth::id();
            
            // Handle name generation or use from request
            if (!isset($request['name']) && isset($request['section'])) {
                $request['name'] = "Class " . $request['section'];
            }

            $schoolClass = $this->schoolClass->create($request);
            
            if (isset($request['subjects']) && is_array($request['subjects'])) {
                $this->syncSubjects($schoolClass, $request['subjects']);
            }
            
            return $schoolClass;
        });
    }

    public function findOrFail(int $id): SchoolClass
    {
        return $this->schoolClass->with(['grade', 'session', 'headTeacher', 'coTeacher', 'subjects'])->findOrFail($id);
    }

    public function update(array $request, string $id): SchoolClass
    {
        return DB::transaction(function () use ($request, $id) {
            $decodedId = customDecoder($id);
            $schoolClass = $this->schoolClass->findOrFail($decodedId);
            $request['updated_by'] = Auth::id();
            
            $schoolClass->update($request);
            
            if (isset($request['subjects']) && is_array($request['subjects'])) {
                $this->syncSubjects($schoolClass, $request['subjects']);
            }
            
            return $schoolClass;
        });
    }

    private function syncSubjects(SchoolClass $schoolClass, array $subjects): void
    {
        $syncData = [];
        foreach ($subjects as $subject) {
            if (isset($subject['subject_id'])) {
                $subjectId = is_string($subject['subject_id']) ? customDecoder($subject['subject_id']) : $subject['subject_id'];
                $teacherId = isset($subject['teacher_id']) ? (is_string($subject['teacher_id']) ? customDecoder($subject['teacher_id']) : $subject['teacher_id']) : null;
                
                $syncData[$subjectId] = [
                    'teacher_id'      => $teacherId,
                    'hours_per_week'  => $subject['hours_per_week'] ?? null,
                    'created_by'      => Auth::id(),
                    'updated_by'      => Auth::id(),
                ];
            }
        }
        $schoolClass->subjects()->sync($syncData);
    }

    public function delete(string $id): void
    {
        $decodedId = customDecoder($id);
        $schoolClass = $this->schoolClass->findOrFail($decodedId);
        $schoolClass->subjects()->detach();
        $schoolClass->delete();
    }
}
