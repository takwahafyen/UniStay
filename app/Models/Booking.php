<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $reference
 * @property int $property_id
 * @property int $student_id
 * @property string $check_in
 * @property string $check_out
 * @property int $guests_count
 * @property float $total_amount
 * @property string $status
 * @property string|null $notes
 */
class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'property_id',
        'student_id',
        'check_in',
        'check_out',
        'guests_count',
        'total_amount',
        'status',
        'notes',
        'confirmed_at',
        'cancelled_at',
    ];

    protected function casts(): array
    {
        return [
            'check_in' => 'date',
            'check_out' => 'date',
            'guests_count' => 'integer',
            'total_amount' => 'decimal:2',
            'confirmed_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
