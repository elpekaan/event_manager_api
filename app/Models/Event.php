<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    use SoftDeletes;

    /**
     * Toplu atama yapılabilecek alanlar
     *
     * @var array<string>
     */
    protected $fillable = [
        // Kimlik
        'uuid',

        // Temel Bilgiler
        'name',
        'slug',
        'description',
        'short_description',

        // Kategori ve Tip
        'category',
        'type',
        'tags',

        // Tarih ve Saat
        'starts_at',
        'ends_at',
        'doors_open_at',
        'sale_starts_at',
        'sale_ends_at',

        // Konum Bilgileri
        'venue_name',
        'venue_address',
        'city',
        'district',
        'country',
        'latitude',
        'longitude',

        // Fiyatlandırma
        'price',
        'min_price',
        'max_price',
        'currency',
        'is_free',

        // Kapasite ve Rezervasyon
        'total_capacity',
        'available_capacity',
        'min_age',
        'max_attendees_per_booking',

        // Cinsiyet Kontrolü
        'has_gender_restriction',
        'gender_restriction_type',
        'male_capacity',
        'female_capacity',

        // Medya
        'cover_image',
        'thumbnail',
        'gallery',

        // Organizatör
        'organizer_id',
        'organizer_name',
        'organizer_phone',
        'organizer_email',

        // Durum ve Görünürlük
        'status',
        'is_featured',
        'is_published',
        'is_cancelled',
        'cancellation_reason',
        'published_at',

        // Ek Özellikler
        'amenities',
        'rules',
        'terms_and_conditions',
        'dress_code',
        'meta',

        // İstatistikler (manuel güncellenenler)
        'rating',
        'review_count',

        // Venue
        'venue_id',
    ];

    /**
     * Tip dönüşümleri
     *
     * @var array<string, string>
     */
    protected $casts = [

        // Tarihler
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'doors_open_at' => 'datetime',
        'sale_starts_at' => 'datetime',
        'sale_ends_at' => 'datetime',
        'published_at' => 'datetime',

        // Boolean'lar
        'is_free' => 'boolean',
        'has_gender_restriction' => 'boolean',
        'is_featured' => 'boolean',
        'is_published' => 'boolean',
        'is_cancelled' => 'boolean',

        // JSON alanları (veritabanında JSON, PHP'de array)
        'tags' => 'array',
        'gallery' => 'array',
        'amenities' => 'array',
        'rules' => 'array',
        'meta' => 'array',

        // Ondalık sayılar
        'price' => 'decimal:2',
        'min_price' => 'decimal:2',
        'max_price' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'rating' => 'decimal:2',

        // Tam sayılar
        'total_capacity' => 'integer',
        'available_capacity' => 'integer',
        'min_age' => 'integer',
        'max_attendees_per_booking' => 'integer',
        'male_capacity' => 'integer',
        'female_capacity' => 'integer',
        'male_count' => 'integer',
        'female_count' => 'integer',
        'view_count' => 'integer',
        'like_count' => 'integer',
        'share_count' => 'integer',
        'review_count' => 'integer',
    ];

    /**
     * API yanıtlarında gizlenecek alanlar
     *
     * @var array<string>
     */
    protected $hidden = [
        'id',           // Dış dünyaya id yerine uuid göster
        'deleted_at',   // Soft delete bilgisi gizli
    ];

    /**
     * Organizatör ilişkisi
     *
     * Bir etkinlik bir organizatöre (kullanıcıya) aittir
     */
    public function organizer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }
}
