<?php

namespace App\Http\Controllers;

use App\Http\Middleware\BelongsToAccount;
use App\Http\Middleware\ValidateJWT;
use App\Http\Middleware\ValidatePropertyCU;
use App\Models\Property;
use App\Models\PropertyAddress;
use App\Models\PropertyRooms;
use App\Models\RoomType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    // Constructor method for PropertyController
    public function __construct()
    {
        $this->middleware(ValidateJWT::class);
        $this->middleware(ValidatePropertyCU::class)->only(['store', 'update']);
        $this->middleware(BelongsToAccount::class)->only(["show", "update", "destroy"]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $user_id = request('user_id');

        $properties = Property::whereUserId($user_id)->get();

        return response()->json([
            "type" => "Successful request",
            "message" => "User properties retrieved successfully",
            "properties" => $properties,
        ], 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function show(Request $request, string $id): JsonResponse
    {
        $property = Property::whereId($id)->first();

        return response()->json([
            "type" => "Successful request",
            "message" => "User property retrieved successfully",
            "property" => $property,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $emoji_arr = ['🪟', '🏚️', '🛖', '🎈', '🏠', '🏡', '🏢', '🏣', '🏤', '🏥', '🏦', '🏨', '🏩', '🏪', '🏫', '🏬', '🏭', '🏯', '🏰', '💒', '🗼', '🗽', '⛪', '🕌', '🕍', '⛩️'];

        if (!request('icon')) $request['icon'] = $emoji_arr[array_rand($emoji_arr)];

        $property = Property::create([
            "user_id" => request('user_id'),
            "icon" => request('icon'),
            "label" => request('label'),
            "description" => request('description')
        ]);

        if (!request('address')) {
            PropertyAddress::create(["property_id" => $property->id]);
        } else {
            PropertyAddress::create([
                ...request("address"),
                "property_id" => $property->id
            ]);
        }

        return response()->json([
            "type" => "Successful request",
            "message" => "Property created successfully",
            "property" => $property->refresh(),
        ], 201);
    }

    public function updateTypes(Request $request, string $id): JsonResponse
    {
        $property = Property::whereId($id)->first();

        $property->type_id = request('type_id');

        $property->save();

        $reqRooms = request('rooms');

        foreach ($reqRooms as $reqRoom) {
            $room = PropertyRooms::wherePropertyId($id)->whereRoomId($reqRoom['id']);

            if ($room->exists()) {
                $room->update(["quantity" => $reqRoom['quantity']]);
            } else {
                PropertyRooms::create([
                    "property_id" => $id,
                    "room_id" => $reqRoom['id'],
                    "quantity" => $reqRoom['quantity']
                ]);
            }
        }

        foreach(PropertyRooms::wherePropertyId($id)->get() as $room) {
            $roomType = RoomType::whereId($room->room_id)->first();

            if (!$roomType->type_id != request('type_id')) $room->delete();
        }

        return response()->json([
            "type" => "Successful request",
            "message" => "User property updated successfully",
            "propertyType" => $property->getTypeAttribute(),
            "propertyRooms" => $property->getRoomsAttribute()
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $property = Property::whereId($id)->first();
        $address = PropertyAddress::wherePropertyId($id)->first();

        $addressData = request('address');

        if (request('icon') !== $property->icon) $property->icon = request('icon');
        if (request('label') !== $property->label) $property->label = request('label');
        if (request('type_id') !== $property->type_id) $property->type_id = request('type_id');
        if (request('description') !== $property->description) $property->description = request('description');

        $property->save();

        if ($addressData["line_1"] !== $address->line_1) $address->line_1 = $addressData["line_1"];
        if ($addressData["line_2"] !== $address->line_2) $address->line_2 = $addressData["line_2"];
        if ($addressData["city"] !== $address->city) $address->city = $addressData["city"];
        if ($addressData["state"] !== $address->state) $address->state = $addressData["state"];
        if ($addressData["zip"] !== $address->zip) $address->zip = $addressData["zip"];

        $address->save();

        return response()->json([
            "type" => "Successful request",
            "message" => "User property updated successfully",
            "property" => $property->refresh()
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(Request $request, string $id): JsonResponse
    {
        $property = Property::whereId($id)->first();

        $property->delete();

        return response()->json([
            "type" => "Successful request",
            "message" => "User property deleted successfully",
        ], 200);
    }
}
