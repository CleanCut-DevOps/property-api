<?php

namespace Tests\Feature\Models;

use App\Models\PropertyType;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PropertyTypeTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Feature test for getting all property types.
     *
     * @return void
     */
    public function test_get_all_property_types(): void
    {
        $response = $this->get('/types');

        $response->assertStatus(Response::HTTP_OK)->assertJsonStructure([
            'type',
            'message',
            'propertyTypes' => [
                '*' => [
                    'id',
                    'label',
                    'description',
                    'available',
                    'created_at',
                    'updated_at'
                ]
            ]
        ]);
    }

    /**
     * Feature test for getting all property types with their rooms types.
     *
     * @return void
     */
    public function test_get_all_property_types_with_rooms(): void
    {
        $response = $this->get('/types?display=withRooms');

        $response->assertStatus(Response::HTTP_OK)->assertJsonStructure([
            'type',
            'message',
            'propertyTypes' => [
                '*' => [
                    'id',
                    'label',
                    'description',
                    'available',
                    'created_at',
                    'updated_at',
                    'rooms'
                ]
            ]
        ]);
    }

    /**
     * Feature test for getting a property type.
     *
     * @return void
     */
    public function test_get_property_type(): void
    {
        $id = PropertyType::first()->id;

        $response = $this->get("/types/$id");

        $response->assertStatus(Response::HTTP_OK)->assertJsonStructure([
            'type',
            'message',
            'propertyType' => [
                'id',
                'label',
                'description',
                'available',
                'created_at',
                'updated_at'
            ]
        ]);
    }

    /**
     * Feature test for getting a property type with its rooms types.
     *
     * @return void
     */
    public function test_get_property_type_with_room_types(): void
    {
        $id = PropertyType::first()->id;

        $response = $this->get("/types/$id?display=withRooms");

        $response->assertStatus(Response::HTTP_OK)->assertJsonStructure([
            'type',
            'message',
            'propertyType' => [
                'id',
                'label',
                'description',
                'available',
                'created_at',
                'updated_at',
                'rooms'
            ]
        ]);
    }

    /**
     * Feature test for getting all rooms types of a property type.
     *
     * @return void
     */
    public function test_get_all_room_types_of_property_type(): void
    {
        $id = PropertyType::first()->id;

        $response = $this->get("/types/$id?display=onlyRooms");

        $response->assertStatus(Response::HTTP_OK)->assertJsonStructure([
            'type',
            'message',
            'roomTypes' => [
                '*' => [
                    'id',
                    'label',
                    'available',
                    'price',
                    'property_type_id',
                    'created_at',
                    'updated_at'
                ]
            ]
        ]);
    }

    /**
     * Feature test for getting a property type that doesn't exist or is blocked.
     *
     * @return void
     */
    public function test_get_invalid_property_type(): void
    {
        $id = fake()->uuid();

        $response = $this->get("/types/$id?display=onlyRooms");

        $response->assertStatus(Response::HTTP_NOT_FOUND)->assertJsonStructure([
            'type',
            'message'
        ]);
    }
}
