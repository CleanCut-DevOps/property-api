<?php

namespace App\Http\Controllers;

use App\Models\PropertyType;
use App\Models\RoomType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    /**
     * Display a listing of the property types.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $propertyTypes = PropertyType::orderBy('label')->get();

        if (request('display') == 'withRooms') {
            foreach($propertyTypes as $propertyType) {
                $propertyType['rooms'] = RoomType::wherePropertyTypeId($propertyType->id)->orderBy('label')->get();
            }
        }

        return response()->json([
            'type' => 'Successful request',
            'message' => 'Displaying attributes of all types of properties',
            'propertyTypes' => $propertyTypes
        ]);
    }

    /**
     * Display the specified property type.
     *
     * @param  string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $propertyType = PropertyType::whereId($id);

        if ($propertyType->exists()) {
            $roomTypes = RoomType::wherePropertyTypeId($propertyType->first()->id)->orderBy('label')->get();

            if (request('display') == 'withRooms') {
                $type = $propertyType->first();
                $type['rooms'] = $roomTypes;

                return response()->json([
                    'type' => 'Successful request',
                    'message' => 'Displaying attributes of this type of property with all its rooms',
                    'propertyType' => $type
                ]);
            } else if (request('display') == 'roomsOnly') {
                return response()->json([
                    'type' => 'Successful request',
                    'message' => 'Displaying attributes of all types of rooms for this property',
                    'roomTypes' => $roomTypes
                ]);
            } else {
                return response()->json([
                    'type' => 'Successful request',
                    'message' => 'Displaying attributes of this type of property',
                    'propertyType' => $propertyType->first()
                ]);
            }
        }

        return response()->json([
            'type' => 'Not found',
            'message' => 'The property type with the given ID does not exist'
        ], 404);
    }
}
