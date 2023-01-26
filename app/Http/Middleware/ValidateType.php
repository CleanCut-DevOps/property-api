<?php

namespace App\Http\Middleware;

use App\Models\PropertyType;
use App\Models\RoomType;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ValidateType
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
                "id" => ["required", "string", "max:255", Rule::exists("type", "id")],
            ], [
                "id.required" => "The type id is required",
                "id.string" => "The type id must be a string",
                "id.max" => "The type id must be less than 255 characters",
                "id.exists" => "The type id does not exist"
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

    protected function validateTypes(Request $request): void
    {
        $request->validate([
            "type_id" => ["required", "string", "max:255"]
        ], [
            "type_id.required" => "The type id is required",
            "type_id.string" => "The type id must be a string",
            "type_id.max" => "The type id must be less than 255 characters"
        ]);

        $request->validate([
            "rooms" => ["nullable", "array"],
            "rooms.*.id" => ["required", Rule::exists("room_type", "id")],
            "rooms.*.quantity" => ["required", "integer", 'min:0']
        ], [
            "rooms.array" => "Rooms attribute must be an array",
            "rooms.*.id.required" => "Room id is required",
            "rooms.*.id.string" => "Room id must be a string",
            "rooms.*.id.max" => "Room id must be less than 255 characters",
            "rooms.*.quantity.required" => "Room quantity is required",
            "rooms.*.quantity.integer" => "Room quantity must be an integer",
            "rooms.*.quantity.min" => "Room quantity must be greater than or equal to 0"
        ]);
    }

    protected function validateExistence(Request $request): void
    {
        $request->validate([
            "type_id" => [Rule::exists('type', 'id')]
        ], [
            "type_id.exists" => "The type id does not exist"
        ]);

        $request->validate([
            "rooms.*.id" => [Rule::exists('room_type', 'id')]
        ], [
            "rooms.*.id.exists" => "The room id does not exist"
        ]);
    }
}
