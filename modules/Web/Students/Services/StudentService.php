<?php

namespace BasicDashboard\Web\Students\Services;

use BasicDashboard\Foundations\Domain\Students\Student;
use BasicDashboard\Foundations\Domain\Guardians\Guardian;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentService
{
    public function __construct(
        private Student $student
    ) {}

    public function paginate(array $request)
    {
        return $this->student
            ->with(['guardians'])
            ->filterByKeyword($request['keyword'] ?? null)
            ->orderByLatest()
            ->paginate($request['paginate'] ?? config('numbers.paginate'));
    }

    public function store(array $request): Student
    {
        return DB::transaction(function () use ($request) {
            $request['created_by'] = Auth::id();
            
            if (empty($request['student_code'])) {
                $request['student_code'] = $this->generateStudentCode();
            }
            
            $student = $this->student->create($request);
            
            $this->saveGuardians($student, $request);
            
            return $student;
        });
    }

    private function generateStudentCode(): string
    {
        $prefix = 'STU-';
        $latest = $this->student->withTrashed()
            ->where('student_code', 'like', $prefix . '%')
            ->orderBy('student_code', 'desc')
            ->first();

        if (!$latest) {
            return $prefix . date('Y') . '0001';
        }

        $lastNumber = (int) substr($latest->student_code, strlen($prefix) + 4);
        $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        
        return $prefix . date('Y') . $nextNumber;
    }

    public function findOrFail(int $id): Student
    {
        return $this->student->with(['guardians'])->findOrFail($id);
    }

    public function update(array $request, string $id): Student
    {
        return DB::transaction(function () use ($request, $id) {
            $decodedId = customDecoder($id);
            $student = $this->student->findOrFail($decodedId);
            $request['updated_by'] = Auth::id();
            $student->update($request);
            
            $this->saveGuardians($student, $request);
            
            return $student;
        });
    }

    private function saveGuardians(Student $student, array $request): void
    {
        $relations = ['father', 'mother', 'guardian'];
        
        foreach ($relations as $prefix) {
            $data = [
                'name'          => $request["{$prefix}_name"] ?? null,
                'nrc'           => $request["{$prefix}_nrc"] ?? null,
                'qualification' => $request["{$prefix}_qualification"] ?? null,
                'job'           => $request["{$prefix}_job"] ?? null,
                'phone'         => $request["{$prefix}_phone"] ?? null,
                'email'         => $request["{$prefix}_email"] ?? null,
                'address'       => $request["{$prefix}_address"] ?? null,
                'alive_status'  => $request["{$prefix}_alive_status"] ?? 'alive',
                'updated_by'    => Auth::id(),
            ];

            if (!$student->guardians()->where('relation', $prefix)->exists()) {
                $data['created_by'] = Auth::id();
                $data['password'] = \Illuminate\Support\Facades\Hash::make('password');
            }

            if ($data['name']) {
                $student->guardians()->updateOrCreate(
                    ['relation' => $prefix],
                    $data
                );
            } else {
                $student->guardians()->where('relation', $prefix)->delete();
            }
        }
    }

    public function delete(string $id): void
    {
        $decodedId = customDecoder($id);
        $student = $this->student->findOrFail($decodedId);
        $student->guardians()->delete();
        $student->delete();
    }
}
