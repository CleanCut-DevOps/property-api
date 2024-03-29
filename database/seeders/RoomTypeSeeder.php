<?php

namespace Database\Seeders;

use App\Models\PropertyType;
use App\Models\RoomType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoomTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $properties = PropertyType::get();

        foreach ($properties as $property) {
            RoomType::factory()->create([
                "property_type_id" => $property->id,
                "label" => "Living room",
                "price" => rand(100, 140) / 10,
            ]);

            RoomType::factory()->create([
                "property_type_id" => $property->id,
                "label" => "Bedroom",
                "price" => rand(80, 100) / 10,
            ]);

            RoomType::factory()->create([
                "property_type_id" => $property->id,
                "label" => "Bathroom",
                "price" => rand(30, 60) / 10,
            ]);

            RoomType::factory()->create([
                "property_type_id" => $property->id,
                "label" => "Dining room",
                "price" => rand(60, 120) / 10,
            ]);

            RoomType::factory()->create([
                "property_type_id" => $property->id,
                "label" => "Utility room",
                "price" => rand(40, 80) / 10,
            ]);
        }
    }
}
