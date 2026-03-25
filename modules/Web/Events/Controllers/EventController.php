<?php

namespace BasicDashboard\Web\Events\Controllers;

use App\Enums\Events\EventType;
use App\Http\Controllers\Controller;
use BasicDashboard\Foundations\Domain\Events\Event;
use BasicDashboard\Web\Events\Resources\EventResource;
use BasicDashboard\Web\Events\Services\EventService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EventController extends Controller
{
    protected $eventService;

    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
    }

    public function index(Request $request)
    {
        $events = $this->eventService->getPaginatedEvents($request->all());

        return Inertia::render('School/Events/Index', [
            'data'    => EventResource::collection($events)->resolve(),
            'meta'    => [
                'total'        => $events->total(),
                'per_page'     => $events->perPage(),
                'current_page' => $events->currentPage(),
                'last_page'    => $events->lastPage(),
                'from'         => $events->firstItem(),
                'to'           => $events->lastItem(),
                'links'        => $events->linkCollection()->toArray(),
            ],
            'filters' => $request->only(['keyword', 'start_date', 'end_date']),
            'types'   => EventType::options(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'date'        => 'required|date',
            'start_time'  => 'required',
            'end_time'    => 'required',
            'location'    => 'required|string',
            'type'        => 'required|string',
        ]);

        $this->eventService->createEvent($data);

        return redirect()->back()->with('success', 'Event created successfully.');
    }

    public function update(Request $request, Event $event)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'date'        => 'required|date',
            'start_time'  => 'required',
            'end_time'    => 'required',
            'location'    => 'required|string',
            'type'        => 'required|string',
        ]);

        $this->eventService->updateEvent($event, $data);

        return redirect()->back()->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event)
    {
        $this->eventService->deleteEvent($event);

        return redirect()->back()->with('success', 'Event deleted successfully.');
    }
}
