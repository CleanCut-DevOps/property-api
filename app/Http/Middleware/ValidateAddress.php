<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ValidateAddress
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
                "line_1" => ["string", "max:255"],
                "line_2" => ["nullable", "string", "max:255"],
                "city" => ["string", "max:255"],
                "state" => ["nullable", "string", "max:255"],
                "zip" => ["numeric"],
            ], [
                "line_1.string" => "Address line 1 must be a string",
                "line_1.max" => "Address line 1 must be less than 255 characters",
                "line_2.string" => "Address line 2 must be a string",
                "line_2.max" => "Address line 2 must be less than 255 characters",
                "city.string" => "City must be a string",
                "city.max" => "City must be less than 255 characters",
                "state.string" => "State must be a string",
                "state.max" => "State must be less than 255 characters",
                "zip.numeric" => "Zip code must be numeric",
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
