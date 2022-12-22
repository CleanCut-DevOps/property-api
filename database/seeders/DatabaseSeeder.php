<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\PropertyType;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $arr = ["Single-family home", "Duplex", "Triplex", "Fourplex", "Condominium", "Townhouse", "Apartment building", "Co-op", "Manufactured home", "Tiny home", "Office building", "Warehouse"];

        foreach ($arr as $typeLabel) {
            PropertyType::create([
                "label" => $typeLabel,
                'bedroom_price' => 16,
                'bathroom_price' => 10,
                'utility_room_price' => 8,
                'kitchen_price' => 12,
                'living_room_price' => 20,
            ]);
        }
    }
}
