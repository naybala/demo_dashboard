<?php

namespace BasicDashboard\Web\Announcements\Controllers;

use App\Enums\Announcements\AnnouncementDepartment;
use App\Enums\Announcements\AnnouncementDestination;
use BasicDashboard\Web\Common\BaseController;
use BasicDashboard\Web\Announcements\Services\AnnouncementService;
use BasicDashboard\Web\Announcements\Resources\AnnouncementResource;
use BasicDashboard\Web\Announcements\Validation\StoreAnnouncementRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class AnnouncementController extends BaseController
{
    const ROUTE = 'announcements';

    public function __construct(
        private AnnouncementService $announcementService
    ) {}

    public function index(Request $request): Response
    {
        $announcements = $this->announcementService->paginate($request->all());
        $announcements = AnnouncementResource::collection($announcements)->response()->getData(true);
        
        return Inertia::render('School/Announcements/Index', [
            'data' => $announcements['data'],
            'meta' => $announcements['meta'],
            'filters' => $request->only(['keyword', 'start_date', 'end_date']),
            'departments' => collect(AnnouncementDepartment::cases())->map(fn($d) => [
                'value' => $d->value,
                'label' => $d->label(),
            ]),
            'destinations' => collect(AnnouncementDestination::cases())->map(fn($d) => [
                'value' => $d->value,
                'label' => $d->label(),
            ]),
        ]);
    }

    public function store(StoreAnnouncementRequest $request): RedirectResponse
    {
        try {
            $this->announcementService->store($request->validated());
            return redirect()->route(self::ROUTE . '.index')->with('success', __('Announcement created successfully'));
        } catch (Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(StoreAnnouncementRequest $request, string $id): RedirectResponse
    {
        try {
            $this->announcementService->update($request->validated(), $id);
            return redirect()->route(self::ROUTE . '.index')->with('success', __('Announcement updated successfully'));
        } catch (Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(string $id): RedirectResponse
    {
        try {
            $this->announcementService->delete($id);
            return redirect()->route(self::ROUTE . '.index')->with('success', __('Announcement deleted successfully'));
        } catch (Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
