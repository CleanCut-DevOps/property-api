<?php

namespace App\Http\Controllers;

use App\Http\Middleware\ValidateImageRequest;
use App\Models\Property;
use App\Models\PropertyImage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function __construct()
    {
        $this->middleware(ValidateImageRequest::class);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $property = Property::whereId(request('id'))->first();

        try {
            $uploadedFile = $request->file('file')->storeOnCloudinary('CleanCut/Property');

            PropertyImage::create([
                "public_id" => $uploadedFile->getPublicId(),
                'property_id' => request('id'),
                'url' => $uploadedFile->getSecurePath()
            ]);

            return response()->json([
                "type" => "Successful request",
                "message" => "Image added successfully to property",
                "property" => $property->refresh(),
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "type" => "Bad Request",
                "message" => "Image could not be added to property",
                "error" => $e->getMessage(),
            ], 400);
        }
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

        $imageModel = PropertyImage::wherePropertyId(request('id'))->whereUrl(request('url'));

        try {
            Cloudinary::destroy($imageModel->first()->public_id);

            $imageModel->delete();

            return response()->json([
                "type" => "Successful request",
                "message" => "Image deleted successfully from property",
                "property" => $property->refresh()
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "type" => "Bad Request",
                "message" => "Image could not be deleted from property",
                "error" => $e->getMessage(),
            ], 400);
        }
    }
}
