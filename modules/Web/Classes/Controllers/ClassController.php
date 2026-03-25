<?php

namespace BasicDashboard\Web\Classes\Controllers;

use BasicDashboard\Web\Common\BaseController;
use BasicDashboard\Web\Classes\Services\ClassService;
use BasicDashboard\Web\Classes\Resources\ClassResource;
use BasicDashboard\Web\Classes\Validation\StoreClassRequest;
use BasicDashboard\Web\Grades\Services\GradeService;
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
        private GradeService $gradeService
    ) {}

    public function index(Request $request): Response
    {
        $classes = $this->classService->paginate($request->all());
        $classes = ClassResource::collection($classes)->response()->getData(true);
        $grades = $this->gradeService->paginate(['paginate' => 1000]); // Get all grades for selection
        
        return Inertia::render('School/Classes/Index', [
            'data' => $classes['data'],
            'meta' => $classes['meta'],
            'grades' => $grades->map(fn($g) => [
                'id' => customEncoder($g->id),
                'name' => $g->name
            ])
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

    public function update(Request $request, string $id): RedirectResponse
    {
        try {
            // Simple update for now, can add Validation request later if needed
            $this->classService->update($request->all(), $id);
            return redirect()->route(self::ROUTE . '.index')->with('success', __('Class updated successfully'));
        } catch (Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
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
