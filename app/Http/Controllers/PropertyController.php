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
use App\Models\PropertyType;
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
            "status" => "Successful request",
            "message" => "User property retrieved successfully",
            "property" => $property,
        ], 201);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $type = PropertyType::whereLabel($request->type)->first();

        $request['type_id'] = $type->id;

        $createdProperty = Property::create($request->all());

        PropertyAddress::create([
            "property_id" => $createdProperty->id,
            "line_1" => $request->address['line_1'],
            "line_2" => $request->address['line_2'],
            "city" => $request->address['city'],
            "state" => $request->address['state'],
            "postal_code" => $request->address['postal_code'],
        ]);

        PropertyRooms::create([
            "property_id" => $createdProperty->id,
            "bedrooms" => $request->rooms['bedrooms'],
            "bathrooms" => $request->rooms['bathrooms'],
            "kitchens" => $request->rooms['kitchens'],
            "living_rooms" => $request->rooms['living_rooms'],
            "utility_rooms" => $request->rooms['utility_rooms'],
        ]);

        foreach ($request->images as $image) {
            PropertyImage::create([
                "property_id" => $createdProperty->id,
                "url" => $image,
            ]);
        }

        return response()->json([
            "type" => "Successful request",
            "message" => "User property created successfully",
            "property" => $createdProperty->refresh(),
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




        if (!empty($request->address)) {
            $address = PropertyAddress::wherePropertyId($id);

            $address->update($request->address);
        }

        if (!empty($request->rooms)) {
            $rooms = PropertyRooms::wherePropertyId($id);

            $rooms->update($request->rooms);
        }

        if (!empty($request->images)) {
            $raw = $property->images()->get();

            $imageURLs =  $raw->map(function ($image) { return $image->url; });

            foreach ($request->images as $url) {
                if (!$imageURLs->contains($url)) {
                    PropertyImage::create([
                        "property_id" => $id,
                        "url" => $url
                    ]);
                }
            }

            foreach ($imageURLs as $url) {
                if (!in_array($url, $request->images)) {
                    $url = PropertyImage::wherePropertyId($id)->whereUrl($url)->first();

                    $url->delete();
                }
            }
        }

        return response()->json([
            "type" => "Successful request",
            "message" => "User property updated successfully",
            "property" => $property->refresh()
        ], 201);
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
        ], 201);
    }
}
