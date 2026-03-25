<?php

namespace BasicDashboard\Web\Subjects\Controllers;

use BasicDashboard\Web\Common\BaseController;
use BasicDashboard\Web\Subjects\Services\SubjectService;
use BasicDashboard\Web\Subjects\Resources\SubjectResource;
use BasicDashboard\Web\Subjects\Validation\StoreSubjectRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class SubjectController extends BaseController
{
    const ROUTE = 'subjects';

    public function __construct(
        private SubjectService $subjectService
    ) {}

    public function index(Request $request): Response
    {
        $subjects = $this->subjectService->paginate($request->all());
        $subjects = SubjectResource::collection($subjects)->response()->getData(true);
        return Inertia::render('School/Subjects/Index', $subjects);
    }

    public function store(StoreSubjectRequest $request): RedirectResponse
    {
        try {
            $this->subjectService->store($request->validated());
            return redirect()->route(self::ROUTE . '.index')->with('success', __('Subject created successfully'));
        } catch (Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(StoreSubjectRequest $request, string $id): RedirectResponse
    {
        try {
            $this->subjectService->update($request->validated(), $id);
            return redirect()->route(self::ROUTE . '.index')->with('success', __('Subject updated successfully'));
        } catch (Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(string $id): RedirectResponse
    {
        try {
            $this->subjectService->delete($id);
            return redirect()->route(self::ROUTE . '.index')->with('success', __('Subject deleted successfully'));
        } catch (Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
