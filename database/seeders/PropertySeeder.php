<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Property;
use App\Models\PropertyType;
use App\Models\Rooms;
use App\Models\RoomType;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class PropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run(): void
    {
        $account_api = config('services.account_api');

        $login = Http::post("$account_api/user/login", [
            'email' => 'test@domain.com',
            'password' => 'Passw0rd',
            'remember' => false
        ]);

        if ($login->failed()) throw new Exception('Failed to login to account API');

        $token = $login->json()['token'];

        $user_id = JWTAuth::setToken($token)->getPayload()->get('sub');

        $propertyTypes = PropertyType::whereAvailable(true)->get();

        foreach ($propertyTypes as $propertyType) {
            $property = Property::factory()
                ->has(Address::factory()->count(1), 'address')
                ->create([
                    'user_id' => $user_id,
                    'type_id' => $propertyType->id,
                ]);

            $roomTypes = RoomType::wherePropertyTypeId($propertyType->id)->get();

            foreach ($roomTypes as $roomType) {
                Rooms::factory()->for($property)->create([
                    'type_id' => $roomType->id
                ]);
            }
        }

    }
}
