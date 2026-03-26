<?php

namespace BasicDashboard\Web\Students\Controllers;

use BasicDashboard\Web\Common\BaseController;
use BasicDashboard\Web\Students\Services\StudentService;
use BasicDashboard\Web\Students\Resources\StudentResource;
use BasicDashboard\Web\Students\Validation\StoreStudentRequest;
use BasicDashboard\Web\Grades\Services\GradeService;
use BasicDashboard\Web\Classes\Services\ClassService;
use BasicDashboard\Web\AcademicSessions\Services\AcademicSessionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class StudentController extends BaseController
{
    const ROUTE = 'students';

    public function __construct(
        private StudentService $studentService,
        private GradeService $gradeService,
        private ClassService $classService,
        private AcademicSessionService $academicSessionService
    ) {}

    public function index(Request $request): Response
    {
        $students = $this->studentService->paginate($request->all());
        $students = StudentResource::collection($students)->response()->getData(true);
        return Inertia::render('School/Students/Index', $students);
    }

    public function create(): Response
    {
        return Inertia::render('School/Students/Create', [
            'grades' => $this->gradeService->paginate(['paginate' => 1000])->map(fn($g) => [
                'id' => customEncoder($g->id),
                'name' => $g->name
            ]),
            'classes' => $this->classService->paginate(['paginate' => 1000])->map(fn($c) => [
                'id' => customEncoder($c->id),
                'name' => $c->name
            ]),
            'academic_sessions' => $this->academicSessionService->paginate(['paginate' => 1000])->map(fn($s) => [
                'id' => customEncoder($s->id),
                'name' => $s->name
            ]),
        ]);
    }

    public function store(StoreStudentRequest $request): RedirectResponse
    {
        try {
            $this->studentService->store($request->validated());
            return redirect()->route(self::ROUTE . '.index')->with('success', __('Student created successfully'));
        } catch (Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function show(string $id): Response
    {
        $decodedId = customDecoder($id);
        $student = $this->studentService->findOrFail($decodedId);
        
        return Inertia::render('School/Students/Show', [
            'student' => clone (new StudentResource($student))->resolve(),
        ]);
    }

    public function edit(string $id): Response
    {
        $decodedId = customDecoder($id);
        $student = $this->studentService->findOrFail($decodedId);
        
        return Inertia::render('School/Students/Create', [
            'student' => new StudentResource($student),
            'grades' => $this->gradeService->paginate(['paginate' => 1000])->map(fn($g) => [
                'id' => customEncoder($g->id),
                'name' => $g->name
            ]),
            'classes' => $this->classService->paginate(['paginate' => 1000])->map(fn($c) => [
                'id' => customEncoder($c->id),
                'name' => $c->name
            ]),
            'academic_sessions' => $this->academicSessionService->paginate(['paginate' => 1000])->map(fn($s) => [
                'id' => customEncoder($s->id),
                'name' => $s->name
            ]),
            'is_editing' => true
        ]);
    }

    public function update(StoreStudentRequest $request, string $id): RedirectResponse
    {
        try {
            $this->studentService->update($request->validated(), $id);
            return redirect()->route(self::ROUTE . '.index')->with('success', __('Student updated successfully'));
        } catch (Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(string $id): RedirectResponse
    {
        try {
            $this->studentService->delete($id);
            return redirect()->route(self::ROUTE . '.index')->with('success', __('Student deleted successfully'));
        } catch (Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
