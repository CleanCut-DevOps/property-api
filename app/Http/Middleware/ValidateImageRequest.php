<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\ValidationException;

class ValidateImageRequest
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (JsonResponse|RedirectResponse) $next
     * @return JsonResponse|RedirectResponse
     */
    public function handle(Request $request, Closure $next): JsonResponse|RedirectResponse
    {
        try {
            $request->validate([
                "url" => [Rule::requiredIf($request->path() == "image/remove"), "string", "max:255", "min:1"],
                "file" => [Rule::requiredIf($request->path() == "image/add"), File::image()->max(12 * 1024)],
                "id" => ["required", Rule::exists("property", "id")],
            ], [
                "url.required" => "Url attribute is required",
                "url.string" => "Image url must be a string",
                "url.max" => "Image url must be less than 255 characters",
                "url.min" => "Image url must be at least 1 character",
                "file.required" => "File attribute is required",
                "file.image" => "File type must be an image",
                "file.max" => "File size must be less than 12MB",
                "id.required" => "id attribute is required",
                "id.exists" => "Property with this id does not exist",
            ]);


            return $next($request);

        } catch (ValidationException $e) {
            $errors = collect($e->errors());

            return response()->json([
                "type" => "Invalid request",
                "message" => $e->getMessage(),
                "errors" => $errors->map(fn($error) => $error[0]),
            ], 400);
        }
    }
}
