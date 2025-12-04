<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Auth middleware zaten kontrol ediyor
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Basic Info
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:10000',
            'short_description' => 'nullable|string|max:500',

            // Category & Type
            'category' => 'required|string|in:concert,theater,sport,party,conference,exhibition,workshop,other',
            'type' => 'nullable|string|max:50',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',

            // Date & Time
            'starts_at' => 'required|date|after:now',
            'ends_at' => 'nullable|date|after:starts_at',
            'doors_open_at' => 'nullable|date|before:starts_at',
            'sale_starts_at' => 'nullable|date',
            'sale_ends_at' => 'nullable|date|after:sale_starts_at|before_or_equal:starts_at',

            // Location
            'venue_name' => 'required|string|max:255',
            'venue_address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'district' => 'nullable|string|max:100',
            'country' => 'nullable|string|size:2',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',

            // Pricing
            'price' => 'nullable|numeric|min:0|max:999999.99',
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|gte:min_price',
            'currency' => 'nullable|string|size:3',
            'is_free' => 'nullable|boolean',

            // Capacity
            'total_capacity' => 'required|integer|min:1|max:1000000',
            'min_age' => 'nullable|integer|min:0|max:100',
            'max_attendees_per_booking' => 'nullable|integer|min:1|max:100',

            // Gender Restriction
            'has_gender_restriction' => 'nullable|boolean',
            'gender_restriction_type' => 'nullable|string|in:male_only,female_only,balanced',
            'male_capacity' => 'nullable|integer|min:0',
            'female_capacity' => 'nullable|integer|min:0',

            // Media
            'cover_image' => 'nullable|string|max:500',
            'thumbnail' => 'nullable|string|max:500',
            'gallery' => 'nullable|array',
            'gallery.*' => 'string|max:500',

            // Organizer
            'organizer_name' => 'nullable|string|max:255',
            'organizer_phone' => 'nullable|string|max:20',
            'organizer_email' => 'nullable|email|max:255',

            // Additional
            'amenities' => 'nullable|array',
            'rules' => 'nullable|array',
            'terms_and_conditions' => 'nullable|string|max:50000',
            'dress_code' => 'nullable|string|in:casual,smart_casual,formal,black_tie,costume,other',
            'meta' => 'nullable|array',
        ];
    }
}
