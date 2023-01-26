<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ValidateRooms
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (JSONResponse|RedirectResponse) $next
     * @return JSONResponse|RedirectResponse
     */
    public function handle(Request $request, Closure $next): RedirectResponse|JSONResponse
    {
        try {
            $request->validate([
                "id" => ["required", "string", "max:255", Rule::exists("room_type", "id")],
                "quantity" => ["required", "numeric"],
            ], [
                "id.required" => "Room type id is required",
                "id.string" => "Room type id must be a string",
                "id.max" => "Room type id must be less than 255 characters",
                "id.exists" => "Room type id does not exist",
                "quantity.required" => "Room quantity is required",
                "quantity.numeric" => "Room quantity must be a number",
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
