<?php

namespace Database\Seeders;

use App\Models\PropertyType;
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
        PropertyType::create([
            "label" => "House",
            "description" => "A structure that serves as a residence, can be found in urban or rural areas.",
            "detailed_description" => "A house is a structure that serves as a residence for one or more individuals, typically with amenities such as bedrooms, bathrooms, and a kitchen. They can come in a variety of styles, sizes, and materials.",
            "available" => true,
        ]);
        PropertyType::create([
            "label" => "Flat / Apartment",
            "description" => "A housing unit that occupies only a portion of a building, more present in urban areas.",
            "detailed_description" => "An apartment or flat is a self-contained housing unit that occupies only a portion of a building. It is typically one of several similar units in a multi-unit building, and is usually rented out or owned separately from the other units.",
            "available" => true,
        ]);
        PropertyType::create([
            "label" => "Manufactured home",
            "description" => "A temporary home built in a factory, can be found in public or private areas.",
            "detailed_description" => "A manufactured home is a structure built off-site and then transported to a location for installation. They may be placed in manufactured home parks or on private land.",
            "available" => true,
        ]);
        PropertyType::create([
            "label" => "Mansions",
            "description" => "A large, luxurious residence, typically situated on a large plot of land.",
            "detailed_description" => "A mansion is a large, luxurious residence, typically with several different types of rooms. They are often grand in appearance and are usually situated on a large plot of land.",
            "available" => true,
        ]);
        PropertyType::create([
            "label" => "Castles",
            "description" => "A type of building built during medieval times, can be found in urban or rural areas.",
            "detailed_description" => "A large, fortified building, often with towers and grand architecture. They are used as a residence for nobility or royalty in the past, but have been converted into museums, hotels or private homes.",
            "available" => false,
        ]);
        PropertyType::create([
            "label" => "Office",
            "description" => "A commercial space to conduct business activities, more present in urban areas.",
            "detailed_description" => "An office is a commercial building or space specifically designed and built for work and business activities. It typically contains several different types of spaces and amenities for the employees and visitors.",
            "available" => true,
        ]);
    }
}
