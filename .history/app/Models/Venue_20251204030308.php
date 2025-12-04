<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Venue extends Model
{
    use SoftDeletes;

    /**
     * Mass assignable attributes
     *
     * @var array<string>
     */
    protected $fillable = [
        'uuid',
        'name',
        'slug',
        'description',
        'address',
        'city',
        'district',
        'country',
        'postal_code',
        'latitude',
        'longitude',
        'phone',
        'email',
        'website',
        'capacity',
        'standing_capacity',
        'seating_capacity',
        'amenities',
        'images',
        'cover_image',
        'owner_id',
        'is_active',
        'is_verified',
    ];

    /**
     * Attribute casting
     *
     * @var array<string, string>
     */
    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'capacity' => 'integer',
        'standing_capacity' => 'integer',
        'seating_capacity' => 'integer',
        'amenities' => 'array',
        'images' => 'array',
        'is_active' => 'boolean',
        'is_verified' => 'boolean',
    ];

    /**
     * Hidden attributes
     *
     * @var array<string>
     */
    protected $hidden = [
        'id',
        'deleted_at',
    ];

    /**
     * Venue owner relationship
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Events at this venue
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }
}
