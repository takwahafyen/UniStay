<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Booking>
 */
class BookingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'reference' => 'BK-' . strtoupper(Str::random(10)),
            'property_id' => Property::factory(),
            'student_id' => User::factory(),
            'check_in' => now()->addDays(2)->toDateString(),
            'check_out' => now()->addMonths(6)->toDateString(),
            'guests_count' => 1,
            'total_amount' => 2400.00,
            'status' => 'pending',
            'notes' => fake()->sentence(),
        ];
    }
}
