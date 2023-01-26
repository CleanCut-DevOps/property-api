<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class ValidateCreateUpdateProperty
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
            $request->validate([
                "icon" => ["string", "max:255", "min:1"],
                "label" => ["string", "max:255"],
                "description" => ["nullable", "string", "between:0,1200"],
            ], [
                "icon.string" => "The icon must be a string",
                "icon.max" => "The icon must be less than 255 characters",
                "icon.min" => "The icon must be at least 1 character",
                "label.string" => "Property label must be a string",
                "label.max" => "Property label must be less than 255 characters",
                "description.string" => "Property description must be a string",
                "description.between" => "Property description must be between 0 and 1200 characters",
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
