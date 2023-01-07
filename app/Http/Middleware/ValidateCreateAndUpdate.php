<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class ValidateCreateAndUpdate
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response|RedirectResponse) $next
     * @return JsonResponse|RedirectResponse
     */
    public function handle(Request $request, Closure $next): JsonResponse|RedirectResponse
    {
        try {
            $this->validateTypes($request);

            if (request('type_id') || count(request('rooms')) > 0) {
                $servicesAPI = config("env.SERVICES_API");

                $response = Http::accept("application/json")->post("$servicesAPI/validate/property", [
                    "type" => request('type_id'),
                    "rooms" => collect(request('rooms'))->map(fn($room) => $room["id"]),
                ]);

                if ($response->successful()) {
                    return $next($request);
                } else {
                    return response()->json($response->json(), 400);
                }
            } else {
                return $next($request);
            }
        } catch (ValidationException $e) {
            $errors = collect($e->errors());

            return response()->json([
                "type" => "Invalid request",
                "message" => $e->getMessage(),
                "errors" => $errors->map(fn($error) => $error[0]),
            ], 400);
        }
    }

    protected function validateTypes(Request $request): void
    {
        $request->validate([
            "icon" => ["nullable", "string", "max:255"],
            "label" => ["nullable", "string", "max:255"],
            "description" => ["nullable", "between:1,1200"],
            "type_id" => ["nullable", "string", "max:255"],
        ], [
            "icon.string" => "The icon must be a string",
            "icon.max" => "The icon must be less than 255 characters",
            "label.string" => "Property label must be a string",
            "label.max" => "Property label must be less than 255 characters",
            "description.between" => "Property description must be between 1 and 1200 characters",
            "type_id.string" => "Property type must be a string",
            "type_id.max" => "Property type must be less than 255 characters",
        ]);

        $request->validate([
            "address" => ["nullable", "array"],
            "address.line_1" => ["nullable", "string", "max:255"],
            "address.line_2" => ["nullable", "string", "max:255"],
            "address.city" => ["nullable", "string", "max:255"],
            "address.state" => ["nullable", "string", "max:255"],
            "address.zip" => ["nullable", "numeric"],
        ], [
            "address.array" => "Address must be an array",
            "address.line_1.string" => "Address line 1 must be a string",
            "address.line_1.max" => "Address line 1 must be less than 255 characters",
            "address.line_2.string" => "Address line 2 must be a string",
            "address.line_2.max" => "Address line 2 must be less than 255 characters",
            "address.city.string" => "City must be a string",
            "address.city.max" => "City must be less than 255 characters",
            "address.state.string" => "State must be a string",
            "address.state.max" => "State must be less than 255 characters",
            "address.zip.numeric" => "Zip code must be numeric",
        ]);

        $request->validate([
            "rooms" => ["nullable", "array"],
            "rooms.*.id" => ["nullable", "string", "max:255"],
            "rooms.*.quantity" => ["nullable", "integer", 'min:0'],
        ], [
            "rooms.array" => "Rooms attribute must be an array",
            "rooms.*.id.string" => "Room id must be a string",
            "rooms.*.id.max" => "Room id must be less than 255 characters",
            "rooms.*.quantity.integer" => "Room quantity must be an integer",
            "rooms.*.quantity.min" => "Room quantity must be greater than or equal to 0",
        ]);

        $request->validate([
            "images" => ["nullable", "array"],
            "images.*" => ["nullable", "string", "max:2048"],
        ], [
            "images.array" => "Images must be an array",
            "images.*.string" => "Image must be a string",
            "images.*.max" => "Image must be less than 2048 characters",
        ]);
    }
}
