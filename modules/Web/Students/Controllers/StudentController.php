<?php

namespace BasicDashboard\Web\Students\Controllers;

use BasicDashboard\Web\Common\BaseController;
use BasicDashboard\Web\Students\Services\StudentService;
use BasicDashboard\Web\Students\Resources\StudentResource;
use BasicDashboard\Web\Students\Validation\StoreStudentRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class StudentController extends BaseController
{
    const ROUTE = 'school.students';

    public function __construct(
        private StudentService $studentService
    ) {}

    public function index(Request $request): Response
    {
        $students = $this->studentService->paginate($request->all());
        $students = StudentResource::collection($students)->response()->getData(true);
        return Inertia::render('School/Students/Index', $students);
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

    public function update(Request $request, string $id): RedirectResponse
    {
        try {
            $this->studentService->update($request->all(), $id);
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
