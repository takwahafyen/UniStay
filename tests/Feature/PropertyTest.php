<?php

namespace Tests\Feature;

use App\Models\Property;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PropertyTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_property_index(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        Property::factory()->create([
            'owner_id' => $owner->id,
            'status' => 'published',
            'is_available' => true,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
