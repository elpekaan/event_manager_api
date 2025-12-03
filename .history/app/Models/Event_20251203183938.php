<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model {

    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'short_description',
        'category',
        'type',
        'tags',
        'starts_at',
        'ends_at',
        'doors_open_at',
        'sale_starts_at',
        'sale_ends_at',
        'venue_name',
        'venue_address',
        'city',
        'district',
        'country',
        'latitude',
        'longitude',
        'price',
        'min_price',
        'max_price',
        'currency',
        'is_free',
        'total_capacity',
        'available_capacity',
        'min_age',
        'max_attendees_per_booking',
        'has_gender_restriction',
        'gender_'
    ];
}
