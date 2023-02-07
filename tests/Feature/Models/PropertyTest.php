<?php

namespace Tests\Feature\Models;

use App\Models\Address;
use App\Models\Property;
use App\Models\PropertyType;
use App\Models\Rooms;
use App\Models\RoomType;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Tests\TestCase;

class PropertyTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Construct a request with a valid token, additionally, created properties if needed
     *
     * @param bool $withProps
     *
     * @return self
     */
    public function withAuth(bool $withProps = false): self
    {
        $account_api = config('services.account_api');

        $response = Http::post("$account_api/user/login", [
            'email' => 'test@domain.com',
            'password' => 'Passw0rd',
            'remember' => false
        ]);

        if ($response->failed()) {
            $this->fail('Failed to login to account API');
        } else {
            $token = $response->json()['token'];

            if ($withProps) {
                $user_id = JWTAuth::setToken($token)->getPayload()->get('sub');

                $property = Property::factory()
                    ->has(Address::factory()->count(1), 'address')
                    ->create([
                        'user_id' => $user_id,
                        'type_id' => PropertyType::first()->id,
                    ]);

                $roomTypes = RoomType::wherePropertyTypeId(PropertyType::first()->id)->get();

                foreach ($roomTypes as $roomType) {
                    Rooms::factory()->for($property)->create([
                        'type_id' => $roomType->id
                    ]);
                }
            }

            return $this->withHeaders(['Authorization' => "Bearer $token"]);
        }
    }

    /**
     * Feature test for getting all user properties.
     *
     * @return void
     */
    public function test_get_all_properties(): void
    {
        $response = $this->withAuth(true)->get('/property');

        $response->assertOk()->assertJsonStructure([
            'type',
            'message',
            'properties' => [
                '*' => [
                    'id',
                    'icon',
                    'user_id',
                    'label',
                    'description',
                    'created_at',
                    'updated_at',
                    'type' => [
                        'id',
                        'label',
                        'description',
                        'created_at',
                        'updated_at',
                    ],
                    'address' => [
                        'line_1',
                        'line_2',
                        'city',
                        'state',
                        'zip'
                    ],
                    'rooms' => [
                        '*' => [
                            'type' => [
                                'id',
                                'label',
                                'price',
                                'created_at',
                                'updated_at',
                            ],
                            'quantity',
                            'updated_at'
                        ]
                    ],
                    'images'
                ]
            ]
        ]);
    }

    /**
     * Feature test for getting all user properties with invalid credentials.
     *
     * @return void
     */
    public function test_get_all_properties_with_invalid_credentials(): void
    {
        $response = $this->get('/property');

        $response->assertUnauthorized()->assertJsonStructure([
            'type',
            'message'
        ]);
    }

    /**
     * Feature test for getting a single property.
     *
     * @return void
     */
    public function test_get_property(): void
    {
        $get = $this->withAuth(true)->get('/property');

        $response = $this->withAuth()->get("/{$get->json()['properties'][0]['id']}");

        $response->assertOk()->assertJsonStructure([
            'type',
            'message',
            'property' => [
                'id',
                'icon',
                'user_id',
                'label',
                'description',
                'created_at',
                'updated_at',
                'type' => [
                    'id',
                    'label',
                    'description',
                    'created_at',
                    'updated_at',
                ],
                'address' => [
                    'line_1',
                    'line_2',
                    'city',
                    'state',
                    'zip'
                ],
                'rooms' => [
                    '*' => [
                        'type' => [
                            'id',
                            'label',
                            'price',
                            'created_at',
                            'updated_at',
                        ],
                        'quantity',
                        'updated_at'
                    ]
                ],
                'images'
            ]
        ]);
    }

    /**
     * Feature test for getting a single property with invalid credentials.
     *
     * @return void
     */
    public function test_get_property_with_invalid_credentials(): void
    {
        $id = Property::first()->id;

        $response = $this->withHeaders([])->get('/$id');

        $response->assertUnauthorized()->assertJsonStructure([
            'type',
            'message'
        ]);
    }

    /**
     * Feature test for getting an invalid property.
     *
     * @return void
     */
    public function test_get_invalid_property(): void
    {
        $id = fake()->uuid;

        $response = $this->withAuth()->get('/$id');

        $response->assertNotFound()->assertJsonStructure([
            'type',
            'message'
        ]);
    }

    /**
     * Feature test for creating a property.
     *
     * @return void
     */
    public function test_create_property(): void
    {
        $response = $this->withAuth()->post('/property');

        $response->assertCreated()->assertJsonStructure([
            'type',
            'message',
            'property' => [
                'id',
                'icon',
                'user_id',
                'label',
                'description',
                'created_at',
                'updated_at',
                'type',
                'address' => [
                    'line_1',
                    'line_2',
                    'city',
                    'state',
                    'zip'
                ],
                'rooms',
                'images'
            ]
        ]);
    }

    /**
     * Feature test for creating a property with invalid data.
     *
     * @return void
     */
    public function test_create_property_with_invalid_data(): void
    {
        $response = $this->withAuth()->post('/property', [
            'type_id' => 'Invalid property type',
            'zip' => 'Invalid zip code'
        ]);

        $response->assertBadRequest()->assertJsonStructure([
            'type',
            'message',
            'errors'
        ]);
    }

    /**
     * Feature test for creating a property with invalid credentials.
     *
     * @return void
     */
    public function test_create_property_with_invalid_credentials(): void
    {
        $response = $this->post('/property');

        $response->assertUnauthorized()->assertJsonStructure([
            'type',
            'message'
        ]);
    }

    /**
     * Feature test for updating a property.
     *
     * @return void
     */
    public function test_update_property(): void
    {
        $get = $this->withAuth(true)->get('/property');

        $response = $this->withAuth()->put("/{$get->json()['properties'][0]['id']}", [
            'label' => 'Updated Label'
        ]);

        $response->assertOk()->assertJsonStructure([
            'type',
            'message'
        ]);
    }

    /**
     * Feature test for updating a property with invalid data.
     *
     * @return void
     */
    public function test_update_property_with_invalid_data(): void
    {
        $get = $this->withAuth(true)->get('/property');

        $response = $this->withAuth()->put("/{$get->json()['properties'][0]['id']}", [
            'label' => 'Updated Label',
            'type_id' => 'Invalid property type',
            'zip' => 'Invalid zip code',
            'rooms' => [
                [
                    'id' => 'Invalid room type',
                ]
            ]
        ]);

        $response->assertBadRequest()->assertJsonStructure([
            'type',
            'message',
            'errors'
        ]);
    }

    /**
     * Feature test for updating a property with invalid credentials.
     *
     * @return void
     */
    public function test_update_property_with_invalid_credentials(): void
    {
        $id = Property::first()->id;

        $response = $this->put('/$id', [
            'label' => 'Some label'
        ]);

        $response->assertUnauthorized()->assertJsonStructure([
            'type',
            'message'
        ]);
    }

    /**
     * Feature test for updating an invalid property.
     *
     * @return void
     */
    public function test_update_invalid_property(): void
    {
        $id = fake()->uuid;

        $response = $this->withAuth()->put('/$id', [
            'label' => 'Some label'
        ]);

        $response->assertNotFound()->assertJsonStructure([
            'type',
            'message'
        ]);
    }

    /**
     * Feature test for deleting a property.
     *
     * @return void
     */
    public function test_delete_property(): void
    {
        $get = $this->withAuth(true)->get('/property');

        $response = $this->withAuth()->delete("/{$get->json()['properties'][0]['id']}");

        $response->assertOk()->assertJsonStructure([
            'type',
            'message'
        ]);
    }

    /**
     * Feature test for deleting a property with invalid credentials.
     *
     * @return void
     */
    public function test_delete_property_with_invalid_credentials(): void
    {
        $id = Property::first()->id;

        $response = $this->delete('/$id');

        $response->assertUnauthorized()->assertJsonStructure([
            'type',
            'message'
        ]);
}

    /**
     * Feature test for deleting an invalid property.
     *
     * @return void
     */
    public function test_delete_invalid_property(): void
    {
        $id = fake()->uuid;

        $response = $this->withAuth()->delete('/$id');

        $response->assertNotFound()->assertJsonStructure([
            'type',
            'message'
        ]);
    }

    /**
     * Feature test for creating a property image.
     *
     * @return void
     */
    public function test_create_property_image(): void
    {
        $get = $this->withAuth(true)->get('/property');

        $response = $this->withAuth()->post("/{$get->json()['properties'][0]['id']}/image", [
            'file' => UploadedFile::fake()->image('test.png', 640, 480)
        ]);

        $response->assertCreated()->assertJsonStructure([
            'type',
            'message',
            'images'
        ]);
    }

    /**
     * Feature test for creating a property image with invalid data.
     *
     * @return void
     */
    public function test_create_property_image_with_invalid_data(): void
    {
        $get = $this->withAuth(true)->get('/property');

        $response = $this->withAuth()->post("/{$get->json()['properties'][0]['id']}/image");

        $response->assertBadRequest()->assertJsonStructure([
            'type',
            'message',
            'errors'
        ]);
    }

    /**
     * Feature test for creating a property image with invalid credentials.
     *
     * @return void
     */
    public function test_create_property_image_with_invalid_credentials(): void
    {
        $id = Property::first()->id;

        $response = $this->post('/$id/image', [
            'file' => UploadedFile::fake()->image('test.png', 640, 480)
        ]);

        $response->assertUnauthorized()->assertJsonStructure([
            'type',
            'message'
        ]);
    }

    /**
     * Feature test for creating a property image with invalid property.
     *
     * @return void
     */
    public function test_create_property_image_with_invalid_property(): void
    {
        $id = fake()->uuid;

        $response = $this->withAuth()->post('/$id/image', [
            'file' => UploadedFile::fake()->image('test.png', 640, 480)
        ]);

        $response->assertNotFound()->assertJsonStructure([
            'type',
            'message'
        ]);
    }

    /**
     * Feature test for deleting a property image.
     *
     * @return void
     */
    public function test_delete_property_image(): void
    {
        $get = $this->withAuth(true)->get('/property');

        $post = $this->withAuth()->post("/{$get->json()['properties'][0]['id']}/image", [
            'file' => UploadedFile::fake()->image('test.png', 640, 480)
        ]);

        $response = $this->withAuth()->delete("/{$get->json()['properties'][0]['id']}/image", [
            'url' => $post->json()['images'][0]
        ]);

        $response->assertOk()->assertJsonStructure([
            'type',
            'message'
        ]);
    }

    /**
     * Feature test for deleting a property image with invalid data.
     *
     * @return void
     */
    public function test_delete_property_image_with_invalid_data(): void
    {
        $get = $this->withAuth(true)->get('/property');

        $response = $this->withAuth()->delete("/{$get->json()['properties'][0]['id']}/image");

        $response->assertBadRequest()->assertJsonStructure([
            'type',
            'message',
            'errors'
        ]);
    }

    /**
     * Feature test for deleting a property image with invalid credentials.
     *
     * @return void
     */
    public function test_delete_property_image_with_invalid_credentials(): void
    {
        $id = Property::first()->id;

        $response = $this->delete('/$id/image', [
            'url' => 'https://example.com/image.png'
        ]);

        $response->assertUnauthorized()->assertJsonStructure([
            'type',
            'message'
        ]);
    }

    /**
     * Feature test for deleting a property image with invalid property.
     *
     * @return void
     */
    public function test_delete_property_image_with_invalid_property(): void
    {
        $id = fake()->uuid;

        $response = $this->withAuth()->delete('/$id/image', [
            'url' => 'https://example.com/image.png'
        ]);

        $response->assertNotFound()->assertJsonStructure([
            'type',
            'message'
        ]);
    }

    /**
     * Feature test for deleting a property image with invalid image.
     *
     * @return void
     */
    public function test_delete_property_image_with_invalid_image(): void
    {
        $get = $this->withAuth(true)->get('/property');

        $response = $this->withAuth()->delete("/{$get->json()['properties'][0]['id']}/image", [
            'url' => 'https://example.com/image.png'
        ]);

        $response->assertNotFound()->assertJsonStructure([
            'type',
            'message'
        ]);
    }
}
