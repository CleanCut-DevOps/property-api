<?php

namespace App\Http\Controllers;

use App\Http\Middleware\BelongsToAccount;
use App\Http\Middleware\ValidateAddress;
use App\Http\Middleware\ValidateCreateUpdateProperty;
use App\Http\Middleware\ValidateJWT;
use App\Http\Middleware\ValidateRooms;
use App\Http\Middleware\ValidateType;
use App\Models\Property;
use App\Models\PropertyAddress;
use App\Models\PropertyRooms;
use App\Models\RoomType;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    // Constructor method for PropertyController
    public function __construct()
    {
        $this->middleware(ValidateJWT::class);
        $this->middleware(ValidateCreateUpdateProperty::class)->only(['store', 'update']);
        $this->middleware(ValidateAddress::class)->only(['updateAddress']);
        $this->middleware(ValidateType::class)->only(['updateType']);
        $this->middleware(ValidateRooms::class)->only(['updateRooms']);
        $this->middleware(BelongsToAccount::class)->only(["show", "update", "updateTypes", "destroy"]);
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
     * Display the specified resource.
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

        $property = Property::create([
            "user_id" => request('user_id'),
            "icon" => request('icon') ?? $emoji_arr[array_rand($emoji_arr)],
            "label" => request('label') ?? "My Property",
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

    /**
     * Update the specified resource in storage.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function updateAddress(string $id): JsonResponse
    {
        PropertyAddress::wherePropertyId($id)->first()->update(request()->only(["line_1", "line_2", "city", "state", "zip"]));

        return response()->json([
            "type" => "Successful request",
            "message" => "Property updated successfully",
            "property" => Property::whereId($id)->first()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function update(string $id): JsonResponse
    {
        $property = Property::whereId($id)->first();

        $property->update(request()->only(["icon", "label", "description"]));

        return response()->json([
            "type" => "Successful request",
            "message" => "Property updated successfully",
            "property" => $property->refresh()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function updateType(string $id): JsonResponse
    {
        $property = Property::whereId($id)->first();

        $property->update(["type_id" => request('id')]);

        PropertyRooms::wherePropertyId($id)->delete();

        foreach (RoomType::whereTypeId(request("id"))->get() as $room) {
            PropertyRooms::create(["quantity" => 0, "property_id" => $id, "room_id" => $room['id']]);
        }

        return response()->json([
            "type" => "Successful request",
            "message" => "Property updated successfully",
            "property" => $property->refresh()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function updateRooms(string $id): JsonResponse
    {
        $check = RoomType::whereId(request('id'))->first()->type_id == Property::whereId($id)->first()->type_id;

        if ($check) {
            PropertyRooms::wherePropertyId($id)->whereRoomId(request('id'))->update(["quantity" => request('quantity')]);

            return response()->json([
                "type" => "Successful request",
                "message" => "Property updated successfully",
                "property" => Property::whereId($id)->first()
            ]);
        } else {
            return response()->json([
                "type" => "Unsuccessful request",
                "message" => "Room type does not belong to property type",
            ], 400);
        }
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
        try {
            Property::whereId($id)->delete();

            return response()->json([
                "type" => "Successful request",
                "message" => "User property deleted successfully",
            ]);
        } catch (Exception $e) {
            return response()->json([
                "type" => "Unsuccessful request",
                "message" => "User property could not be deleted",
            ], 400);
        }
    }
}
