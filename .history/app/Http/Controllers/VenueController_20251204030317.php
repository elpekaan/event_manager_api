<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class VenueController extends Controller
{
    /**
     * Display a listing of venues
     *
     * GET /api/venues
     */
    public function index(): JsonResponse
    {
        $venues = Venue::query()
            ->where('is_active', true)
            ->orderBy('name', 'asc')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $venues,
        ]);
    }

    /**
     * Store a newly created venue
     *
     * POST /api/venues
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'district' => 'nullable|string|max:100',
            'country' => 'nullable|string|size:2',
            'postal_code' => 'nullable|string|max:20',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'capacity' => 'nullable|integer|min:1',
            'standing_capacity' => 'nullable|integer|min:0',
            'seating_capacity' => 'nullable|integer|min:0',
            'amenities' => 'nullable|array',
            'cover_image' => 'nullable|string|max:500',
        ]);

        $validated['uuid'] = Str::uuid();
        $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(6);
        $validated['owner_id'] = $request->user()->id;

        $venue = Venue::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Venue created successfully.',
            'data' => $venue,
        ], 201);
    }

    /**
     * Display the specified venue
     *
     * GET /api/venues/{venue}
     */
    public function show(Venue $venue): JsonResponse
    {
        $venue->load('events');

        return response()->json([
            'success' => true,
            'data' => $venue,
        ]);
    }

    /**
     * Update the specified venue
     *
     * PUT /api/venues/{venue}
     */
    public function update(Request $request, Venue $venue): JsonResponse
    {
        // Only owner can update
        if ($venue->owner_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to perform this action.',
            ], 403);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string|max:5000',
            'address' => 'sometimes|string|max:500',
            'city' => 'sometimes|string|max:100',
            'district' => 'nullable|string|max:100',
            'country' => 'nullable|string|size:2',
            'postal_code' => 'nullable|string|max:20',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'capacity' => 'nullable|integer|min:1',
            'standing_capacity' => 'nullable|integer|min:0',
            'seating_capacity' => 'nullable|integer|min:0',
            'amenities' => 'nullable|array',
            'cover_image' => 'nullable|string|max:500',
            'is_active' => 'sometimes|boolean',
        ]);

        if (isset($validated['name'])) {
            $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(6);
        }

        $venue->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Venue updated successfully.',
            'data' => $venue,
        ]);
    }

    /**
     * Remove the specified venue
     *
     * DELETE /api/venues/{venue}
     */
    public function destroy(Request $request, Venue $venue): JsonResponse
    {
        // Only owner can delete
        if ($venue->owner_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to perform this action.',
            ], 403);
        }

        $venue->delete();

        return response()->json([
            'success' => true,
            'message' => 'Venue deleted successfully.',
        ]);
    }
}
