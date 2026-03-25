<?php

namespace BasicDashboard\Web\Classes\Controllers;

use BasicDashboard\Web\Common\BaseController;
use BasicDashboard\Web\Classes\Services\ClassService;
use BasicDashboard\Web\Classes\Resources\ClassResource;
use BasicDashboard\Web\Classes\Validation\StoreClassRequest;
use BasicDashboard\Web\Grades\Services\GradeService;
use BasicDashboard\Web\Subjects\Services\SubjectService;
use BasicDashboard\Web\AcademicSessions\Services\AcademicSessionService;
use BasicDashboard\Web\Users\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class ClassController extends BaseController
{
    const ROUTE = 'classes';

    public function __construct(
        private ClassService $classService,
        private GradeService $gradeService,
        private SubjectService $subjectService,
        private AcademicSessionService $sessionService,
        private UserService $userService
    ) {}

    public function index(Request $request): Response
    {
        $classes = $this->classService->paginate($request->all());
        $classes = ClassResource::collection($classes)->response()->getData(true);
        
        return Inertia::render('School/Classes/Index', [
            'data' => $classes['data'],
            'meta' => $classes['meta'],
            'filters' => $request->only(['keyword'])
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('School/Classes/Create', [
            'grades' => $this->gradeService->paginate(['paginate' => 1000])->map(fn($g) => [
                'id' => customEncoder($g->id),
                'name' => $g->name
            ]),
            'sessions' => $this->sessionService->paginate(['paginate' => 1000])->map(fn($s) => [
                'id' => customEncoder($s->id),
                'name' => $s->name
            ]),
            'subjects' => $this->subjectService->paginate(['paginate' => 1000])->map(fn($s) => [
                'id' => customEncoder($s->id),
                'name' => $s->name
            ]),
            'teachers' => $this->userService->paginate(['paginate' => 1000])->filter(fn($u) => $u->user_type->value == 2)->map(fn($u) => [
                'id' => customEncoder($u->id),
                'fullname' => $u->fullname
            ])->values(),
        ]);
    }

    public function store(StoreClassRequest $request): RedirectResponse
    {
        try {
            $this->classService->store($request->validated());
            return redirect()->route(self::ROUTE . '.index')->with('success', __('Class created successfully'));
        } catch (Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function edit(string $id): Response
    {
        $decodedId = customDecoder($id);
        $schoolClass = $this->classService->findOrFail($decodedId);
        
        return Inertia::render('School/Classes/Create', [
            'editData' => new ClassResource($schoolClass),
            'grades' => $this->gradeService->paginate(['paginate' => 1000])->map(fn($g) => [
                'id' => customEncoder($g->id),
                'name' => $g->name
            ]),
            'sessions' => $this->sessionService->paginate(['paginate' => 1000])->map(fn($s) => [
                'id' => customEncoder($s->id),
                'name' => $s->name
            ]),
            'subjects' => $this->subjectService->paginate(['paginate' => 1000])->map(fn($s) => [
                'id' => customEncoder($s->id),
                'name' => $s->name
            ]),
            'teachers' => $this->userService->paginate(['paginate' => 1000])->filter(fn($u) => $u->user_type->value == 2)->map(fn($u) => [
                'id' => customEncoder($u->id),
                'fullname' => $u->fullname
            ])->values(),
        ]);
    }

    public function update(StoreClassRequest $request, string $id): RedirectResponse
    {
        try {
            $this->classService->update($request->validated(), $id);
            return redirect()->route(self::ROUTE . '.index')->with('success', __('Class updated successfully'));
        } catch (Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function show(string $id): Response
    {
        $decodedId = customDecoder($id);
        $schoolClass = $this->classService->findOrFail($decodedId);
        
        return Inertia::render('School/Classes/Show', [
            'classInfo' => new ClassResource($schoolClass)
        ]);
    }

    public function destroy(string $id): RedirectResponse
    {
        try {
            $this->classService->delete($id);
            return redirect()->route(self::ROUTE . '.index')->with('success', __('Class deleted successfully'));
        } catch (Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
