<?php

namespace Database\Seeders;

use App\Models\PropertyType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PropertyTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        PropertyType::factory()->create([
            "label" => "House",
            "description" => "A structure that serves as a residence, can be found in urban or rural areas.",
        ]);
        PropertyType::factory()->create([
            "label" => "Flat / Apartment",
            "description" => "A housing unit that occupies only a portion of a building, more present in urban areas.",
        ]);
        PropertyType::factory()->create([
            "label" => "Manufactured home",
            "description" => "A temporary home built in a factory, can be found in public or private areas.",
        ]);
        PropertyType::factory()->create([
            "label" => "Mansion",
            "description" => "A large, luxurious residence, typically situated on a large plot of land.",
        ]);
        PropertyType::factory()->create([
            "label" => "Castle",
            "description" => "A type of building built during medieval times, can be found in urban or rural areas.",
        ]);
        PropertyType::factory()->create([
            "label" => "Office",
            "description" => "A commercial space to conduct business activities, more present in urban areas.",
        ]);
    }
}
