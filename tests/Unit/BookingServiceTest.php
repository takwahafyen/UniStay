<?php

namespace Tests\Unit;

use App\Models\Booking;
use App\Models\Property;
use App\Models\User;
use App\Services\BookingService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Tests\TestCase;

class BookingServiceTest extends TestCase
{
    use RefreshDatabase;

    protected BookingService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new BookingService;
    }

    public function test_cannot_book_with_past_date(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $student = User::factory()->create(['role' => 'student']);
        $property = Property::factory()->create(['owner_id' => $owner->id]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('La date d\'arrivée ne peut pas être dans le passé.');

        $this->service->createBooking(
            property: $property,
            student: $student,
            checkIn: Carbon::yesterday()->toDateString(),
            checkOut: Carbon::tomorrow()->toDateString()
        );
    }

    public function test_cannot_book_if_end_date_before_start_date(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $student = User::factory()->create(['role' => 'student']);
        $property = Property::factory()->create(['owner_id' => $owner->id]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('La date de départ doit être postérieure à la date d\'arrivée.');

        $this->service->createBooking(
            property: $property,
            student: $student,
            checkIn: Carbon::tomorrow()->toDateString(),
            checkOut: Carbon::today()->toDateString()
        );
    }

    public function test_unauthorized_user_cannot_cancel_booking(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $student = User::factory()->create(['role' => 'student']);
        $intruder = User::factory()->create(['role' => 'student']);
        $property = Property::factory()->create(['owner_id' => $owner->id]);

        $booking = Booking::factory()->create([
            'property_id' => $property->id,
            'student_id' => $student->id,
            'status' => 'pending',
        ]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Action non autorisée.');

        $this->service->cancelBooking($booking, $intruder);
    }
}
