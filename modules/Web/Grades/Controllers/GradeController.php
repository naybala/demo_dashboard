<?php

namespace BasicDashboard\Web\Grades\Controllers;

use BasicDashboard\Web\Common\BaseController;
use BasicDashboard\Web\Grades\Services\GradeService;
use BasicDashboard\Web\Grades\Resources\GradeResource;
use BasicDashboard\Web\Grades\Validation\StoreGradeRequest;
use BasicDashboard\Web\Grades\Validation\UpdateGradeRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class GradeController extends BaseController
{
    const ROUTE = 'school.grades';
    const LANG_PATH = "school.grade";

    public function __construct(
        private GradeService $gradeService
    ) {}

    public function index(Request $request): Response
    {
        $grades = $this->gradeService->paginate($request->all());
        $grades = GradeResource::collection($grades)->response()->getData(true);
        return Inertia::render('School/Grades/Index', $grades);
    }

    public function store(StoreGradeRequest $request): RedirectResponse
    {
        try {
            $this->gradeService->store($request->validated());
            return redirect()->route(self::ROUTE . '.index')->with('success', __('Grade created successfully'));
        } catch (Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(UpdateGradeRequest $request, string $id): RedirectResponse
    {
        try {
            $this->gradeService->update($request->validated(), $id);
            return redirect()->route(self::ROUTE . '.index')->with('success', __('Grade updated successfully'));
        } catch (Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(string $id): RedirectResponse
    {
        try {
            $this->gradeService->delete($id);
            return redirect()->route(self::ROUTE . '.index')->with('success', __('Grade deleted successfully'));
        } catch (Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
