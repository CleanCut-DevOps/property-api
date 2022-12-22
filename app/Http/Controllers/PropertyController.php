<?php

namespace App\Http\Controllers;

use App\Http\Middleware\BelongsToAccount;
use App\Http\Middleware\ValidateCreate;
use App\Http\Middleware\ValidateJWT;
use App\Http\Middleware\ValidateUpdate;
use App\Models\Property;
use App\Models\PropertyAddress;
use App\Models\PropertyImage;
use App\Models\PropertyRooms;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    // Constructor method for PropertyController
    public function __construct()
    {
        $this->middleware(ValidateJWT::class);
        $this->middleware(ValidateCreate::class)->only("store");
        $this->middleware(ValidateUpdate::class)->only("update");
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
        $user_id = $request->user_id;

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
        $property = Property::create($request->all());

        PropertyAddress::create([
            "property_id" => $property->id,
            "line_1" => $request->address['line_1'],
            "line_2" => $request->address['line_2'],
            "city" => $request->address['city'],
            "state" => $request->address['state'],
            "postal_code" => $request->address['postal_code'],
        ]);

        foreach ($request->rooms as $rawRoomData) {
            PropertyRooms::create([
                "property_id" => $property->id,
                "room_id" => $rawRoomData['id'],
                "quantity" => $rawRoomData['quantity'],
            ]);
        }

        foreach ($request->images as $imageURL) {
            PropertyImage::create([
                "property_id" => $property->id,
                "url" => $imageURL,
            ]);
        }

        return response()->json([
            "type" => "Successful request",
            "message" => "User property created successfully",
            "property" => $property->refresh(),
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $property = Property::whereId($id)->first();

        $property->update($request->all());

        $property->address->update($request->address);

        return response()->json([
            "type" => "Successful request",
            "message" => "User property updated successfully",
            "property" => $property->refresh()
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Request  $request
     * @param  string $id
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
