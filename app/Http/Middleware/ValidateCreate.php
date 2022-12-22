<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class ValidateCreate
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

            $serviceAPI = config("env.SERVICE_API");

            $response = Http::accept('application/json')->post("$serviceAPI/api/validate/property", [
                'type' => $request->type_id,
                'rooms' => collect($request->rooms)->map(fn ($room) => $room["id"]),
            ]);

            if ($response->successful()) {
                return $next($request);
            } else {
                return response()->json($response->json(), 400);
            }
        } catch (ValidationException $e) {
            $errors = array_merge(...array_values($e->errors()));

            return response()->json([
                "type" => "Invalid request",
                "message" => $e->getMessage(),
                "errors" => $errors
            ], 400);
        }
    }

    protected function validateTypes(Request $request): void
    {
        $request->validate([
            "name" => ["required", "string", "max:255"],
            "description" => ["required", "between:1,1200"],
            "type_id" => ["required", "string"],
        ], [
            "name.required" => "Property name is required",
            "name.string" => "Property name must be a string",
            "name.max" => "Property name must be less than 255 characters",
            "description.required" => "Property description is required",
            "description.between" => "Property description must be between 1 and 1200 characters",
            "type_id.required" => "Property type is required",
            "type_id.string" => "Property type must be a string",
        ]);

        $request->validate([
            "address" => ['required', 'array'],
            "address.line_1" => ['required', 'string', 'max:255'],
            "address.line_2" => ['nullable', 'string', 'max:255'],
            "address.city" => ['required', 'string', 'max:48'],
            "address.state" => ['nullable', 'string', 'max:48'],
            "address.postal_code" => ['required', 'numeric'],
        ], [
            "address.required" => "Property address is required",
            "address.array" => "Address must be an array",
            "address.line_1.required" => "Address line 1 is required",
            "address.line_1.string" => "Address line 1 must be a string",
            "address.line_1.max" => "Address line 1 must be less than 255 characters",
            "address.line_2.string" => "Address line 2 must be a string",
            "address.line_2.max" => "Address line 2 must be less than 255 characters",
            "address.city.required" => "Address city is required",
            "address.city.string" => "Address city must be a string",
            "address.city.max" => "Address city must be less than 48 characters",
            "address.state.string" => "Address state must be a string",
            "address.state.max" => "Address state must be less than 48 characters",
            "address.postal_code.required" => "Address postal code is required",
            "address.postal_code.numeric" => "Address postal code must be a number",
        ]);

        $request->validate([
            "rooms" => ['required', 'array'],
            "rooms.*.id" => ['required', 'string', 'max:255'],
            "rooms.*.quantity" => ['required', 'integer', 'min:1'],
        ], [
            "rooms.required" => "Property rooms are required",
            "rooms.array" => "Rooms must be an array",
            "rooms.*.id.required" => "Room id is required",
            "rooms.*.id.string" => "Room id must be a string",
            "rooms.*.id.max" => "Room id must be less than 255 characters",
            "rooms.*.quantity.required" => "Room quantity is required",
            "rooms.*.quantity.integer" => "Room quantity must be an integer",
            "rooms.*.quantity.min" => "Room quantity must be at least 1",
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
