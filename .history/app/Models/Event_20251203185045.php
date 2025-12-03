<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        'male_count',
        'female_count',

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

        /
    ]

}
