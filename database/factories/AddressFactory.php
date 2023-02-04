<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'property_id' => Property::factory(),
            'line_1' => fake()->streetAddress,
            'line_2' => null,
            'city' => fake()->city,
            'state' => null,
            'zip' => fake()->postcode,
        ];
    }
}
