<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {

            // PRIMARY KEY
            $table->id();
            $table->uuid('uuid')->unique(); // Dış dünyaya açık benzersiz kimlik

            // TEMEL BİLGİLER
            $table->string('name'); // Etkinlik adı
            $table->string('slug')->unique(); // URL-friendly isim (konser-2024)
            $table->text('description')->nullable(); // Detaylı açıklama
            $table->text('short_description')->nullable(); // Kısa açıklama (liste için)

            // KATEGORİ VE TİP
            $table->string('category'); // Kategori: concert, theater, sport, party, conference
            $table->string('type')->default('standard'); // Tip: standard, vip, premium
            $table->json('tags')->nullable(); // Etiketler: ["müzik", "canlı", "outdoor"]

            // TARİH VE SAAT
            $table->dateTime('starts_at'); // Başlangıç tarihi/saati
            $table->dateTime('ends_at')->nullable(); // Bitiş tarihi/saati
            $table->dateTime('doors_open_at')->nullable(); // Kapıların açılış saati
            $table->dateTime('sale_starts_at')->nullable(); // Bilet satış başlangıcı
            $table->dateTime('sale_ends_at')->nullable(); // Bilet satış bitişi

            // KONUM BİLGİLERİ
            $table->string('venue_name'); // Mekan adı
            $table->string('venue_address'); // Adres
            $table->string('city'); // Şehir
            $table->string('district')->nullable(); // İlçe
            $table->string('country')->default('TR'); // Ülke kodu
            $table->decimal('latitude', 10, 8)->nullable(); // Enlem (harita için)
            $table->decimal('longitude', 11, 8)->nullable(); // Boylam (harita için)

            // ═══════════════════════════════════════════════════════════════
            // FİYATLANDIRMA
            // ═══════════════════════════════════════════════════════════════
            $table->decimal('price', 10, 2)->default(0); // Temel fiyat
            $table->decimal('min_price', 10, 2)->nullable(); // Minimum fiyat (aralık için)
            $table->decimal('max_price', 10, 2)->nullable(); // Maksimum fiyat
            $table->string('currency', 3)->default('TRY'); // Para birimi
            $table->boolean('is_free')->default(false); // Ücretsiz mi?

            // ═══════════════════════════════════════════════════════════════
            //  KAPASİTE VE REZERVASYON
            // ═══════════════════════════════════════════════════════════════
            $table->unsignedInteger('total_capacity'); // Toplam kapasite
            $table->unsignedInteger('available_capacity'); // Mevcut kapasite
            $table->unsignedInteger('min_age')->nullable(); // Minimum yaş (18+)
            $table->unsignedInteger('max_attendees_per_booking')->default(10); // Tek seferde max bilet

            // ═══════════════════════════════════════════════════════════════
            //  CİNSİYET KONTROLÜ
            // ═══════════════════════════════════════════════════════════════
            $table->boolean('has_gender_restriction')->default(false); // Cinsiyet kısıtlaması var mı?
            $table->string('gender_restriction_type')->nullable(); // male_only, female_only, balanced
            $table->unsignedInteger('male_capacity')->nullable(); // Erkek kapasitesi
            $table->unsignedInteger('female_capacity')->nullable(); // Kadın kapasitesi
            $table->unsignedInteger('male_count')->default(0); // Mevcut erkek sayısı
            $table->unsignedInteger('female_count')->default(0); // Mevcut kadın sayısı

            // ═══════════════════════════════════════════════════════════════
            //  MEDYA
            // ═══════════════════════════════════════════════════════════════
            $table->string('cover_image')->nullable(); // Kapak resmi
            $table->string('thumbnail')->nullable(); // Küçük resim
            $table->json('gallery')->nullable(); // Galeri resimleri ["img1.jpg", "img2.jpg"]

            // ═══════════════════════════════════════════════════════════════
            //  ORGANİZATÖR
            // ═══════════════════════════════════════════════════════════════
            $table->foreignId('organizer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('organizer_name')->nullable(); // Organizatör adı
            $table->string('organizer_phone')->nullable(); // İletişim telefonu
            $table->string('organizer_email')->nullable(); // İletişim email

            // ═══════════════════════════════════════════════════════════════
            //  DURUM VE GÖRÜNÜRLÜK
            // ═══════════════════════════════════════════════════════════════
            $table->enum('status', ['draft', 'pending', 'published', 'cancelled', 'completed', 'postponed'])->default('draft');
            $table->boolean('is_featured')->default(false); // Öne çıkan mı?
            $table->boolean('is_published')->default(false); // Yayında mı?
            $table->boolean('is_cancelled')->default(false); // İptal mi?
            $table->string('cancellation_reason')->nullable(); // İptal sebebi
            $table->dateTime('published_at')->nullable(); // Yayınlanma tarihi

            // ═══════════════════════════════════════════════════════════════
            // EK ÖZELLİKLER
            // ═══════════════════════════════════════════════════════════════
            $table->json('amenities')->nullable(); // Olanaklar: ["parking", "wifi", "food"]
            $table->json('rules')->nullable(); // Kurallar: ["no_smoking", "dress_code"]
            $table->text('terms_and_conditions')->nullable(); // Şartlar ve koşullar
            $table->string('dress_code')->nullable(); // Kıyafet kuralı: casual, formal, smart
            $table->json('meta')->nullable(); // Ekstra JSON veriler

            // ═══════════════════════════════════════════════════════════════
            // İSTATİSTİKLER
            // ═══════════════════════════════════════════════════════════════
            $table->unsignedBigInteger('view_count')->default(0); // Görüntülenme
            $table->unsignedBigInteger('like_count')->default(0); // Beğeni
            $table->unsignedBigInteger('share_count')->default(0); // Paylaşım
            $table->decimal('rating', 3, 2)->nullable(); // Ortalama puan (4.75)
            $table->unsignedInteger('review_count')->default(0); // Yorum sayısı

            // ═══════════════════════════════════════════════════════════════
            // ZAMAN DAMGALARI
            // ═══════════════════════════════════════════════════════════════
            $table->timestamps(); // created_at, updated_at
            $table->softDeletes(); // deleted_at (silme işlemi geri alınabilir)

            // ═══════════════════════════════════════════════════════════════
            // İNDEKSLER (Performans için)
            // ═══════════════════════════════════════════════════════════════
            $table->index('category');
            $table->index('city');
            $table->index('status');
            $table->index('starts_at');
            $table->index('is_published');
            $table->index('is_featured');
            $table->index(['city', 'category', 'starts_at']); // Kompozit indeks
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
