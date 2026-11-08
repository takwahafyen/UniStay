<?php

namespace Database\Factories;

use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Property>
 */
class PropertyFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->sentence(4);

        return [
            'owner_id' => User::factory()->create(['role' => 'owner'])->id,
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->randomNumber(4),
            'description' => fake()->paragraph(),
            'type' => fake()->randomElement(['studio', 'apartment', 'room', 'shared_flat']),
            'price_monthly' => fake()->randomFloat(2, 200, 1000),
            'deposit' => 300.00,
            'charges_included' => true,
            'surface_sqm' => fake()->numberBetween(15, 80),
            'rooms_count' => fake()->numberBetween(1, 4),
            'bathrooms_count' => 1,
            'address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'postal_code' => fake()->postcode(),
            'latitude' => fake()->latitude(35, 37),
            'longitude' => fake()->longitude(9, 11),
            'is_furnished' => true,
            'is_available' => true,
            'status' => 'published',
        ];
    }
}
