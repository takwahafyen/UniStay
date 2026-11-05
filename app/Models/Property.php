<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;

/**
 * @property int $id
 * @property int $owner_id
 * @property string $title
 * @property string $slug
 * @property string $description
 * @property string $type
 * @property float $price_monthly
 * @property float $deposit
 * @property bool $charges_included
 * @property int $surface_sqm
 * @property int $rooms_count
 * @property int $bathrooms_count
 * @property string $address
 * @property string $city
 * @property string $postal_code
 * @property float|null $latitude
 * @property float|null $longitude
 * @property bool $is_furnished
 * @property bool $is_available
 * @property string $status
 */
class Property extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'owner_id',
        'title',
        'slug',
        'description',
        'type',
        'price_monthly',
        'deposit',
        'charges_included',
        'surface_sqm',
        'rooms_count',
        'bathrooms_count',
        'address',
        'city',
        'postal_code',
        'latitude',
        'longitude',
        'is_furnished',
        'is_available',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'price_monthly' => 'decimal:2',
            'deposit' => 'decimal:2',
            'charges_included' => 'boolean',
            'surface_sqm' => 'integer',
            'rooms_count' => 'integer',
            'bathrooms_count' => 'integer',
            'latitude' => 'float',
            'longitude' => 'float',
            'is_furnished' => 'boolean',
            'is_available' => 'boolean',
        ];
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(Amenity::class, 'amenity_property');
    }

    public function images(): HasMany
    {
        return $this->hasMany(PropertyImage::class)->orderBy('order');
    }

    public function primaryImage(): HasMany
    {
        return $this->hasMany(PropertyImage::class)->where('is_primary', true);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Elasticsearch Scout index configuration
     */
    public function searchableAs(): string
    {
        return 'properties_index';
    }

    /**
     * Custom representation for Elasticsearch indexing
     *
     * @return array<string, mixed>
     */
    public function toSearchableArray(): array
    {
        $array = [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'type' => $this->type,
            'price_monthly' => (float) $this->price_monthly,
            'surface_sqm' => (int) $this->surface_sqm,
            'rooms_count' => (int) $this->rooms_count,
            'city' => $this->city,
            'postal_code' => $this->postal_code,
            'is_furnished' => (bool) $this->is_furnished,
            'is_available' => (bool) $this->is_available,
            'status' => $this->status,
            'amenities' => $this->amenities->pluck('slug')->toArray(),
            'created_at' => $this->created_at?->toIso8601String(),
        ];

        if ($this->latitude && $this->longitude) {
            $array['location'] = [
                'lat' => (float) $this->latitude,
                'lon' => (float) $this->longitude,
            ];
        }

        return $array;
    }

    /**
     * Determine if model should be indexed in Elasticsearch
     */
    public function shouldBeSearchable(): bool
    {
        return $this->status === 'published' && $this->is_available;
    }
}
