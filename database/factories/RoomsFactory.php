<?php

namespace Database\Factories;

use App\Models\Property;
use App\Models\Rooms;
use App\Models\RoomType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Rooms>
 */
class RoomsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type_id' => RoomType::factory(),
            'property_id' => Property::factory(),
            'quantity' => fake()->numberBetween(1, 10),
        ];
    }
}
