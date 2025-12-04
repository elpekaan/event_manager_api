<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
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

        ])
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        //
    }
}
