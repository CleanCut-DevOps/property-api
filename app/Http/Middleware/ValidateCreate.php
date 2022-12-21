<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

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
            $request->validate([
                "name" => ["required", "string", "max:255"],
                "description" => ["required", "between:0,1200"],
                "type" => ["required", Rule::in(["Single-family home", "Duplex", "Triplex", "Fourplex", "Condominium", "Townhouse", "Apartment building", "Co-op", "Manufactured home", "Tiny home", "Office building", "Warehouse"])],

                "address" => ['required', 'array'],
                "address.line_1" => ['required', 'string', 'max:255'],
                "address.line_2" => ['nullable', 'string', 'max:255'],
                "address.city" => ['required', 'string', 'max:48'],
                "address.state" => ['nullable', 'string', 'max:48'],
                "address.postal_code" => ['required', 'numeric'],

                "rooms" => ['required', 'array'],
                "rooms.bedrooms" => ['required', 'numeric'],
                "rooms.bathrooms" => ['required', 'numeric'],
                "rooms.kitchens" => ['required', 'numeric'],
                "rooms.living_rooms" => ['required', 'numeric'],
                "rooms.utility_rooms" => ['required', 'numeric'],

                "images" => ["nullable", "array"],
                "images.*" => ["nullable", "string", "max:2048"],
            ]);

            return $next($request);
        } catch (ValidationException $e) {
            $errors = array_merge(...array_values($e->errors()));

            return response()->json([
                "type" => "Invalid request",
                "message" => $e->getMessage(),
                "errors" => $errors
            ], 400);
        }
    }
}
