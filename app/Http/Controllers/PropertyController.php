<?php

namespace App\Http\Controllers;

use App\Http\Middleware\BelongsToAccount;
use App\Http\Middleware\ValidateCreate;
use App\Http\Middleware\ValidateJWT;
use App\Http\Middleware\ValidateUpdate;
use App\Models\Property;
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
        $created = Property::create([
            "user_id" => $request->user_id,
            "name" => request("name"),
            "address" => request("address"),
            "bedrooms" => request("bedrooms"),
            "bathrooms" => request("bathrooms"),
            "description" => request("description"),
            "price" => request("price"),
            "sq_ft" => request("sq_ft"),
            "type" => request("type"),
        ]);

        foreach ($request->images as $imagePath) {
            $created->images()->create([
                "path" => $imagePath,
            ]);
        }

        $property = Property::whereId($created->id)->first();

        return response()->json([
            "type" => "Successful request",
            "message" => "User property created successfully",
            "property" => $property,
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

        if (!empty($request->name)) $property->name = $request->name;
        if (!empty($request->type)) $property->type = $request->type;
        if (!empty($request->price)) $property->price = $request->price;
        if (!empty($request->sq_ft)) $property->sq_ft = $request->sq_ft;
        if (!empty($request->address)) $property->address = $request->address;
        if (!empty($request->bedrooms)) $property->bedrooms = $request->bedrooms;
        if (!empty($request->bathrooms)) $property->bathrooms = $request->bathrooms;
        if (!empty($request->description)) $property->description = $request->description;

        $propertyImages = $property->images()->get();

        foreach ($request->images as $imageURL) {
            if (!$propertyImages->contains("path", $imageURL)) {
                $property->images()->create([
                    "path" => $imageURL,
                ]);
            }
        }

        foreach ($propertyImages as $image) {
            if (!in_array($image->path, $request->images)) {
                $image->delete();
            }
        }

        $property->save();

        return response()->json([
            "type" => "Successful request",
            "message" => "User property updated successfully",
            "property" => $property
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
