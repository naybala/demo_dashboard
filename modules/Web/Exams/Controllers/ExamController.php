<?php

namespace BasicDashboard\Web\Exams\Controllers;

use BasicDashboard\Web\Common\BaseController;
use BasicDashboard\Web\Exams\Services\ExamService;
use BasicDashboard\Web\Exams\Resources\ExamResource;
use BasicDashboard\Web\Exams\Validation\StoreExamRequest;
use BasicDashboard\Web\AcademicSessions\Services\AcademicSessionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class ExamController extends BaseController
{
    const ROUTE = 'exams';

    public function __construct(
        private ExamService $examService,
        private AcademicSessionService $academicSessionService
    ) {}

    public function index(Request $request): Response
    {
        $exams = $this->examService->paginate($request->all());
        $exams = ExamResource::collection($exams)->response()->getData(true);
        $sessions = $this->academicSessionService->paginate(['paginate' => 1000]);
        
        return Inertia::render('School/Exams/Index', [
            'data' => $exams['data'],
            'meta' => $exams['meta'],
            'academic_sessions' => $sessions->map(fn($s) => [
                'id' => customEncoder($s->id),
                'name' => $s->name
            ])
        ]);
    }

    public function store(StoreExamRequest $request): RedirectResponse
    {
        try {
            $this->examService->store($request->validated());
            return redirect()->route(self::ROUTE . '.index')->with('success', __('Exam created successfully'));
        } catch (Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        try {
            $this->examService->update($request->all(), $id);
            return redirect()->route(self::ROUTE . '.index')->with('success', __('Exam updated successfully'));
        } catch (Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(string $id): RedirectResponse
    {
        try {
            $this->examService->delete($id);
            return redirect()->route(self::ROUTE . '.index')->with('success', __('Exam deleted successfully'));
        } catch (Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
