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
use Symfony\Component\Console\Output\ConsoleOutput;

class ValidateTypeCU
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
            $this->validateTypes($request);
            $this->validateExistence($request);

            $reqType = PropertyType::whereId(request('type_id'));

            if (!$reqType->first()->available) {
                return response()->json([
                    "type" => "Resource not available",
                    "message" => "The requested property type is not available",
                    "errors" => [
                        "type_id" => "The requested property type is not available"
                    ]
                ], 400);
            }

            $reqRooms = request('rooms');

            if ($reqRooms) {
                foreach ($reqRooms as $room) {
                    $reqRoomType = RoomType::whereId($room['id'])->first();

                    if (!$reqRoomType->available) {
                        return response()->json([
                            "type" => "Resource not available",
                            "message" => "The requested room type is not available",
                            "errors" => [
                                "rooms" => "The requested room type is not available"
                            ]
                        ], 400);
                    }

                    if ($reqRoomType->type_id != request('type_id')) {
                        return response()->json([
                            "type" => "Invalid request",
                            "message" => "The requested room type does not belong to the requested property type",
                            "errors" => [
                                "rooms" => "The requested room type does not belong to the requested property type"
                            ]
                        ], 400);
                    }
                }
            }

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
