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
    public function update(Request $request, Event $event): JsonResponse
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

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'category' => 'sometimes|string',
            'type' => 'nullable|string',
            'starts_at' => 'sometimes|date',
            'ends_at' => 'nullable|date|after:starts_at',
            'venue_name' => 'sometimes|string|max:255',
            'venue_address' => 'sometimes|string',
            'city' => 'sometimes|string|max:100',
            'district' => 'nullable|string|max:100',
            'price' => 'nullable|numeric|min:0',
            'total_capacity' => 'sometimes|integer|min:1',
            'status' => 'sometimes|in:draft,pending,published,cancelled',
            'is_published' => 'sometimes|boolean',
            'is_featured' => 'sometimes|boolean',
        ]);

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
