<?php

namespace App\Http\Controllers;

use App\Models\PropertyType;
use App\Models\RoomType;
use Illuminate\Http\JsonResponse;

class TypeController extends Controller
{
    /**
     * Display a listing of property types.
     *
     * @return JsonResponse
     */
    public function indexProperty(): JsonResponse
    {
        $propertyTypes = PropertyType::orderBy('label')->get();

        if (request('withRooms') == "true") {
            foreach($propertyTypes as $propertyType) {
                $propertyType['rooms'] = RoomType::whereTypeId($propertyType->id)->orderBy('label')->get();
            }
        }

        return response()->json([
            "type" => "Successful request",
            "message" => "Displaying attributes of all types of properties",
            "propertyTypes" => $propertyTypes
        ]);
    }

    /**
     * Display a listing of room types under a property type.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function showPropertyRooms(string $id): JsonResponse
    {
        $propertyType = PropertyType::whereId($id);

        if (!$propertyType->exists()) {
            return response()->json([
                "type" => "Not found",
                "message" => "The property type with the given ID does not exist"
            ], 404);
        }

        $roomTypes = RoomType::whereTypeId($id)->orderBy('label')->get();

        return response()->json([
            "type" => "Successful request",
            "message" => "Displaying attributes of all types of rooms for this property",
            "roomTypes" => $roomTypes
        ]);
    }

    /**
     * Display the specified property type.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function showProperty(string $id): JsonResponse
    {
        $propertyType = PropertyType::whereId($id);

        if (!$propertyType->exists()) {
            return response()->json([
                "type" => "Not found",
                "message" => "The property type with the given ID does not exist",
            ], 404);
        } else {
            $propertyType['rooms'] = RoomType::whereTypeId($id)->orderBy('label')->get();

            return response()->json([
                "type" => "Successful request",
                "message" => "Displaying attributes of this type of property",
                "propertyType" => $propertyType->first()
            ]);
        }
    }

    /**
     * Display the specified room type.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function showRoom(string $id): JsonResponse
    {
        $roomType = RoomType::whereId($id);

        if (!$roomType->exists()) {
            return response()->json([
                "type" => "Not found",
                "message" => "The room type with the given ID does not exist",
            ], 404);
        }

        return response()->json([
            "type" => "Successful request",
            "message" => "Displaying attributes of this type of room",
            "roomType" => $roomType->first()
        ]);
    }


//    /**
//     * Store a newly created property type in storage.
//     *
//     * @param  Request  $request
//     * @return JsonResponse
//     */
//    public function storeProperty(Request $request): JsonResponse
//    {
//        //
//    }
//
//    /**
//     * Store a newly created room type in storage.
//     *
//     * @param  Request  $request
//     * @return JsonResponse
//     */
//    public function storeRoom(Request $request): JsonResponse
//    {
//        //
//    }
//
//    /**
//     * Update the specified property type in storage.
//     *
//     * @param  Request  $request
//     * @param  string $id
//     * @return JsonResponse
//     */
//    public function updateProperty(Request $request, string $id): JsonResponse
//    {
//        //
//    }
//
//    /**
//     * Update the specified room type in storage.
//     *
//     * @param  Request  $request
//     * @param  string $id
//     * @return JsonResponse
//     */
//    public function updateRoom(Request $request, string $id): JsonResponse
//    {
//        //
//    }
//
//    /**
//     * Remove the specified property type with their corresponding room types from storage.
//     *
//     * @param  string $id
//     * @return JsonResponse
//     */
//    public function destroy(string $id): JsonResponse
//    {
//        //
//    }
//
//    /**
//     * Remove the specified room type with their corresponding room types from storage.
//     *
//     * @param  string $id
//     * @return JsonResponse
//     */
//    public function destroy(string $id): JsonResponse
//    {
//        //
//    }
}
