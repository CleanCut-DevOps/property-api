<?php

namespace App\Http\Controllers;

use App\Http\Middleware\BelongsToAccount;
use App\Http\Middleware\ValidateCreateAndUpdate;
use App\Http\Middleware\ValidateJWT;
use App\Models\Property;
use App\Models\PropertyAddress;
use App\Models\PropertyImage;
use App\Models\PropertyRooms;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class PropertyController extends Controller
{
    // Constructor method for PropertyController
    public function __construct()
    {
        $this->middleware(ValidateJWT::class);
        $this->middleware(ValidateCreateAndUpdate::class)->only(['store', 'update']);
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
        $emoji_arr = ['🪟','🏚️','🛖','🎈', '🏠', '🏡', '🏢', '🏣', '🏤', '🏥', '🏦', '🏨', '🏩', '🏪', '🏫', '🏬', '🏭', '🏯', '🏰', '💒', '🗼', '🗽', '⛪', '🕌', '🕍', '⛩️'];

        if (!request('icon')) $request['icon'] = $emoji_arr[array_rand($emoji_arr)];

        $property = Property::create($request->all());

        if (!request('address')) {
            PropertyAddress::create(["property_id" => $property->id]);
        } else {
            PropertyAddress::create([
                ...request("address"),
                "property_id" => $property->id
            ]);
        }

        if (count(request('images')) > 0) {
            foreach(request('images') as $imageURL) {
                PropertyImage::create([
                    "property_id" => $property->id,
                    "url" => $imageURL
                ]);
            }
        }

        if (count(request('rooms')) > 0) {
            foreach(request('rooms') as $room) {
                PropertyRooms::create([
                    "property_id" => $property->id,
                    "room_id" => $room["id"],
                    "quantity" => $room["quantity"]
                ]);
            }
        }

        return response()->json([
            "type" => "Successful request",
            "message" => "Property created successfully",
            "property" => $property->refresh(),
        ], 201);
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
        $propertyAddress = $property->address;
        $propertyImages = $property->images;

        if ($property->type != $request->type) PropertyRooms::wherePropertyId($id)->delete();

        $property->update($request->all());

        $propertyAddress->update($request->address);

        foreach ($request->rooms as $rawRoomData) {
            $selectedPropertyRoom = PropertyRooms::wherePropertyId($id)->whereRoomId($rawRoomData['id']);

            if ($selectedPropertyRoom->exists()) {
                if ($rawRoomData['quantity'] < 0) {
                    $selectedPropertyRoom->update([
                        "quantity" => 0,
                    ]);

                } else {
                    $selectedPropertyRoom->update([
                        "quantity" => $rawRoomData['quantity'],
                    ]);

                }

            } else {
                if ($rawRoomData['quantity'] < 0) {
                    PropertyRooms::create([
                        "property_id" => $property->id,
                        "room_id" => $rawRoomData['id'],
                        "quantity" => 0,
                    ]);

                } else {
                    PropertyRooms::create([
                        "property_id" => $property->id,
                        "room_id" => $rawRoomData['id'],
                        "quantity" => $rawRoomData['quantity'],
                    ]);

                }

            }
        }

        foreach ($request->images as $imageURL) {
            $selectedPropertyImage = PropertyImage::wherePropertyId($id)->whereUrl($imageURL);

            if (!$selectedPropertyImage->exists()) {
                PropertyImage::create([
                    "property_id" => $property->id,
                    "url" => $imageURL,
                ]);
            }
        }

        foreach ($propertyImages as $propertyImage) {
            if (!in_array($propertyImage, $request->images)) {
                PropertyImage::wherePropertyId($id)->whereUrl($propertyImage)->delete();
            }
        }

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
