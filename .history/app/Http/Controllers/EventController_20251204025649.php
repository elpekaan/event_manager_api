<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\Event;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;

class EventController extends Controller
{
    /**
     * Get all published events.
     *
     * GET /api/events
     */
    public function index(): JsonResponse
    {
        $events = Event::query()
            ->where('is_published', true)
            ->orderBy('starts_at', 'asc')
            ->paginate(15);

        return response()->json(
            [
                'success' => true,
                'data' => $events
            ]
        );
    }

    /**
     * Create a new event.
     *
     * POST /api/events
     */
    public function store(StoreEventRequest $request): JsonResponse
    {
        $validated = $request->validated();

        // UUID ve slug otomatik oluştur.
        $validated['uuid'] = Str::uuid();
        $validated['slug'] = Str::slug($validated['name'] . '-' . \Str::random(6));
        $validated['available_capacity'] = $validated['total_capacity'];
        $validated['status'] = 'draft';
        $validated['organizer_id'] = $request->user()->id;

        $event = Event::create($validated);

        return response()->json(
            [
                'success' => true,
                'message' => 'Event created successfully.',
                'data' => $event
            ],
            201
        );
    }

    /**
     * Get the specified event.
     *
     * GET /api/events/{event}
     */
    public function show(Event $event): JsonResponse
    {
        // Görüntülenme sayısını artır
        $event->increment('view_count');

        return response()->json(
            [
                'success' => true,
                'data' => $event
            ]
        );
    }

    /**
     * Update the specified event.
     *
     * PUT/PATCH /api/events/{event}
     */
    public function update(UpdateEventRequest $request, Event $event): JsonResponse
    {
        if ($event->organizer_id !== $request->user()->id) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'You are not authorized to update this event.'
                ],
                403
            );
        }

        $validated = $request->validated();

        // İsim değiştiyse slug'ı güncelle
        if (isset($validated['name'])) {
            $validated['slug'] = \Str::slug($validated['name'] . '-' . \Str::random(6));
        }

        $event->update($validated);

        return response()->json(
            [
                'success' => true,
                'message' => 'Event updated successfully.',
                'data' => $event
            ]
        );
    }

    /**
     * Delete the specified event. (Soft Delete)
     *
     * DELETE /api/events/{event}
     */
    public function destroy(Request $request, Event $event): JsonResponse
    {
        if ($event->organizer_id !== $request->user()->id) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'You are not authorized to update this event.'
                ],
                403
            );
        }

        $event->delete();

        return response()->json(
            [
                'success' => true,
                'message' => 'Event deleted successfully.'
            ]
        );
    }
}
