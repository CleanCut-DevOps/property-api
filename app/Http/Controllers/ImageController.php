<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $property = Property::whereId(request('id'))->first();

        return response()->json([
            "type" => "Successful request",
            "message" => "Image added successfully to property",
            "property" => $property,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(Request $request): JsonResponse
    {
        $property = Property::whereId(request('id'))->first();

        return response()->json([
            "type" => "Successful request",
            "message" => "Image deleted successfully from property",
            "property" => $property,
        ], 200);
    }
}
