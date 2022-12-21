<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ValidateUpdate
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
                "name" => ["nullable", "string", "max:255"],
                "description" => ["nullable", "between:0,1200"],
                "type" => ["nullable", Rule::in(["Single-family home", "Duplex", "Triplex", "Fourplex", "Condominium", "Townhouse", "Apartment building", "Co-op", "Manufactured home", "Tiny home", "Office building", "Warehouse"])],

                "address" => ['nullable', 'array'],
                "address.line_1" => ['nullable', 'string', 'max:255'],
                "address.line_2" => ['nullable', 'string', 'max:255'],
                "address.city" => ['nullable', 'string', 'max:48'],
                "address.state" => ['nullable', 'string', 'max:48'],
                "address.postal_code" => ['nullable', 'numeric'],

                "rooms" => ['nullable', 'array'],
                "rooms.bedrooms" => ['nullable', 'numeric'],
                "rooms.bathrooms" => ['nullable', 'numeric'],
                "rooms.kitchens" => ['nullable', 'numeric'],
                "rooms.living_rooms" => ['nullable', 'numeric'],
                "rooms.utility_rooms" => ['nullable', 'numeric'],

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
