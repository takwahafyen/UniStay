<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Property;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;

class BookingService
{
    /**
     * Create a booking with race condition / double-booking prevention using pessimistic locking
     *
     * @throws InvalidArgumentException
     */
    public function createBooking(Property $property, User $student, string $checkIn, string $checkOut, int $guests = 1, ?string $notes = null): Booking
    {
        $startDate = Carbon::parse($checkIn)->startOfDay();
        $endDate = Carbon::parse($checkOut)->startOfDay();

        if ($startDate->isPast()) {
            throw new InvalidArgumentException('La date d\'arrivée ne peut pas être dans le passé.');
        }

        if ($endDate->lessThanOrEqualTo($startDate)) {
            throw new InvalidArgumentException('La date de départ doit être postérieure à la date d\'arrivée.');
        }

        $monthsCount = max(1, $startDate->diffInMonths($endDate));
        $totalAmount = $property->price_monthly * $monthsCount;

        return DB::transaction(function () use ($property, $student, $startDate, $endDate, $guests, $totalAmount, $notes) {
            // Pessimistic lock on the property to prevent concurrent double-booking
            $lockedProperty = Property::where('id', $property->id)->lockForUpdate()->firstOrFail();

            if (! $lockedProperty->is_available || $lockedProperty->status !== 'published') {
                throw new InvalidArgumentException('Ce logement n\'est pas disponible à la location.');
            }

            // Check if there is an overlapping confirmed or pending booking
            $hasOverlap = Booking::where('property_id', $lockedProperty->id)
                ->whereIn('status', ['pending', 'confirmed'])
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('check_in', [$startDate, $endDate])
                        ->orWhereBetween('check_out', [$startDate, $endDate])
                        ->orWhere(function ($sub) use ($startDate, $endDate) {
                            $sub->where('check_in', '<=', $startDate)
                                ->where('check_out', '>=', $endDate);
                        });
                })
                ->exists();

            if ($hasOverlap) {
                throw new InvalidArgumentException('Ce logement est déjà réservé sur la période sélectionnée.');
            }

            return Booking::create([
                'reference' => 'BK-'.strtoupper(Str::random(10)),
                'property_id' => $lockedProperty->id,
                'student_id' => $student->id,
                'check_in' => $startDate->toDateString(),
                'check_out' => $endDate->toDateString(),
                'guests_count' => $guests,
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'notes' => $notes,
            ]);
        });
    }

    /**
     * Cancel booking safely with ownership check (Anti-IDOR)
     */
    public function cancelBooking(Booking $booking, User $user): bool
    {
        if ($booking->student_id !== $user->id && $booking->property->owner_id !== $user->id && ! $user->isAdmin()) {
            throw new InvalidArgumentException('Action non autorisée.');
        }

        if ($booking->status === 'cancelled') {
            return false;
        }

        $booking->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

        return true;
    }
}
