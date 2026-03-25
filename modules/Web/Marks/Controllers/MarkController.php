<?php

namespace BasicDashboard\Web\Marks\Controllers;

use BasicDashboard\Web\Common\BaseController;
use BasicDashboard\Web\Marks\Services\MarkService;
use BasicDashboard\Web\Marks\Resources\MarkResource;
use BasicDashboard\Web\Marks\Validation\StoreMarkRequest;
use BasicDashboard\Web\Students\Services\StudentService;
use BasicDashboard\Web\Exams\Services\ExamService;
use BasicDashboard\Web\Subjects\Services\SubjectService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class MarkController extends BaseController
{
    const ROUTE = 'marks';

    public function __construct(
        private MarkService $markService,
        private StudentService $studentService,
        private ExamService $examService,
        private SubjectService $subjectService
    ) {}

    public function index(Request $request): Response
    {
        $marks = $this->markService->paginate($request->all());
        $marks = MarkResource::collection($marks)->response()->getData(true);
        
        $students = $this->studentService->paginate(['paginate' => 1000]);
        $exams = $this->examService->paginate(['paginate' => 1000]);
        $subjects = $this->subjectService->paginate(['paginate' => 1000]);
        
        return Inertia::render('School/Marks/Index', [
            'data' => $marks['data'],
            'meta' => $marks['meta'],
            'students' => $students->map(fn($s) => ['id' => customEncoder($s->id), 'name' => $s->name]),
            'exams' => $exams->map(fn($e) => ['id' => customEncoder($e->id), 'name' => $e->name]),
            'subjects' => $subjects->map(fn($s) => ['id' => customEncoder($s->id), 'name' => $s->name]),
        ]);
    }

    public function store(StoreMarkRequest $request): RedirectResponse
    {
        try {
            $this->markService->store($request->validated());
            return redirect()->route(self::ROUTE . '.index')->with('success', __('Mark recorded successfully'));
        } catch (Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        try {
            $this->markService->update($request->all(), $id);
            return redirect()->route(self::ROUTE . '.index')->with('success', __('Mark updated successfully'));
        } catch (Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(string $id): RedirectResponse
    {
        try {
            $this->markService->delete($id);
            return redirect()->route(self::ROUTE . '.index')->with('success', __('Mark deleted successfully'));
        } catch (Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
