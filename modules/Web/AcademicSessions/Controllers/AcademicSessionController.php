<?php

namespace BasicDashboard\Web\AcademicSessions\Controllers;

use BasicDashboard\Web\Common\BaseController;
use BasicDashboard\Web\AcademicSessions\Services\AcademicSessionService;
use BasicDashboard\Web\AcademicSessions\Resources\AcademicSessionResource;
use BasicDashboard\Web\AcademicSessions\Validation\StoreAcademicSessionRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class AcademicSessionController extends BaseController
{
    const ROUTE = 'academic-sessions';

    public function __construct(
        private AcademicSessionService $academicSessionService
    ) {}

    public function index(Request $request): Response
    {
        $sessions = $this->academicSessionService->paginate($request->all());
        $sessions = AcademicSessionResource::collection($sessions)->response()->getData(true);
        return Inertia::render('School/AcademicSessions/Index', $sessions);
    }

    public function store(StoreAcademicSessionRequest $request): RedirectResponse
    {
        try {
            $this->academicSessionService->store($request->validated());
            return redirect()->route(self::ROUTE . '.index')->with('success', __('Academic Session created successfully'));
        } catch (Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        try {
            $this->academicSessionService->update($request->all(), $id);
            return redirect()->route(self::ROUTE . '.index')->with('success', __('Academic Session updated successfully'));
        } catch (Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(string $id): RedirectResponse
    {
        try {
            $this->academicSessionService->delete($id);
            return redirect()->route(self::ROUTE . '.index')->with('success', __('Academic Session deleted successfully'));
        } catch (Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
