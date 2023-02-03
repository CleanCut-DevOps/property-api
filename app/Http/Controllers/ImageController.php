<?php

namespace App\Http\Controllers;

use App\Http\Middleware\BelongsToAccount;
use App\Http\Middleware\ValidateJWT;
use App\Models\Images;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\File;

class ImageController extends Controller
{
    public function __construct()
    {
        $this->middleware(ValidateJWT::class);
        $this->middleware(BelongsToAccount::class);

        $this->validate('store', [
            'file' => ['required', File::image()->max(12 * 1024)]
        ], [
            'file.required' => 'File attribute is required',
            'file.image' => 'File type must be an image',
            'file.max' => 'File size must be less than 12MB'

        ]);

        $this->validate('destroy', [
            'url' => ['required', 'string', 'max:255', 'min:1']
        ], [
            'url.required' => 'Url attribute is required',
            'url.string' => 'Image url must be a string',
            'url.max' => 'Image url must be less than 255 characters',
            'url.min' => 'Image url must be at least 1 character'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param string $id
     *
     * @return JsonResponse
     */
    public function store(Request $request, string $id): JsonResponse
    {
        try {
            $upload = Cloudinary::upload($request->file('file')->getRealPath(), [
                'folder' => 'CleanCut/Property',
                'secure' => true
            ]);
        } catch (Exception $e) {
            return response()->json([
                'type' => 'Bad Request',
                'message' => 'Image could not be added to property',
                'error' => $e->getMessage(),
            ], 400);
        }

        Images::create([
            'public_id' => $upload->getPublicId(),
            'property_id' => $id,
            'url' => $upload->getSecurePath()
        ]);

        return response()->json([
            'type' => 'Successful request',
            'message' => 'Image added successfully to property',
            'images' => Images::wherePropertyId($id)->get()->map(fn($image) => $image->url)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param string $id
     *
     * @return JsonResponse
     */
    public function destroy(Request $request, string $id): JsonResponse
    {
        $imageModel = Images::wherePropertyId($id)->whereUrl($request->url);

        if ($imageModel->exists() > 0) {
            try {
                Cloudinary::destroy($imageModel->first()->public_id);

            } catch (Exception $e) {
                return response()->json([
                    'type' => 'Bad Request',
                    'message' => 'Image could not be deleted from property',
                    'error' => $e->getMessage(),
                ], 400);
            }

            $imageModel->delete();

            return response()->json([
                'type' => 'Successful request',
                'message' => 'Image deleted successfully from property',
                'images' => Images::wherePropertyId($id)->get()->map(fn($image) => $image->url)
            ], 200);
        } else {
            return response()->json([
                'type' => 'Not found',
                'message' => 'Image not found',
            ], 404);
        }
    }
}
