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
     * Display a listing of events with filtering
     *
     * GET /api/events
     */
    public function index(Request $request): JsonResponse
    {
        $query = Event::query()->with('venue');

        // Only show published events (unless admin)
        $query->where('is_published', true);

        // Search by name or description
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by city
        if ($request->has('city')) {
            $query->where('city', $request->input('city'));
        }

        // Filter by category
        if ($request->has('category')) {
            $query->where('category', $request->input('category'));
        }

        // Filter by venue
        if ($request->has('venue_id')) {
            $query->where('venue_id', $request->input('venue_id'));
        }

        // Filter by date range
        if ($request->has('from')) {
            $query->where('starts_at', '>=', $request->input('from'));
        }
        if ($request->has('to')) {
            $query->where('starts_at', '<=', $request->input('to'));
        }

        // Filter by price range
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->input('min_price'));
        }
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->input('max_price'));
        }

        // Filter by free events
        if ($request->has('is_free')) {
            $query->where('is_free', $request->boolean('is_free'));
        }

        // Filter by featured
        if ($request->has('is_featured')) {
            $query->where('is_featured', $request->boolean('is_featured'));
        }

        // Sorting
        $sortField = $request->input('sort', 'starts_at');
        $sortOrder = $request->input('order', 'asc');

        $allowedSorts = ['starts_at', 'price', 'name', 'created_at', 'view_count'];
        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortOrder === 'desc' ? 'desc' : 'asc');
        }

        // Pagination
        $perPage = $request->input('per_page', 15);
        $perPage = min($perPage, 100); // Maximum 100 per page

        $events = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $events,
        ]);
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
        // Load venue relationship
        $event->load('venue');

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
