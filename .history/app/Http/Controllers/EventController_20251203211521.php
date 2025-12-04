<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Js;
use Symfony\Component\HttpFoundation\JsonResponse;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
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
     * Store a newly created resource in storage.
     *
     * POST /api/events
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'category' => 'required|string',
            'type' => 'nullable|string',
            'starts_at' => 'required|date',
            'ends_at' => 'nullable|date|after:starts_at',
            'venue_name' => 'required|string|max:255',
            'venue_address' => 'required|string',
            'city' => 'required|string|max:100',
            'district' => 'nullable|string|max:100',
            'price' => 'nullable|numeric|min:0',
            'total_capacity' => 'required|integer|min:1',
        ]);

        // UUID ve slug otomatik oluştur.
        $validated['uuid'] = \Str::uuid();
        $validated['slug'] = \Str::slug($validated['name'] . '-' . \Str::random(6));
        $validated['available_capacity'] = $validated['total_capacity'];
        $validated['status'] = 'draft';

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
     * Display the specified resource.
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
     * Update the specified resource in storage.
     *
     * PUT/PATCH /api/events/{event}
     */
    public function update(Request $request, Event $event): JsonResponse
    {
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
     * Remove the specified resource from storage.
     *
     * DELETE /api/events/{event}
     */
    public function destroy(Event $event)
    {
        //
    }
}
