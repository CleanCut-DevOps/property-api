<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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
                "address" => ["required", "string", "max:255"],
                "bedrooms" => ["required", "integer"],
                "bathrooms" => ["required", "integer"],
                "description" => ["required", "between:30,600"],
                "price" => ["required", "numeric"],
                "sq_ft" => ["nullable", "numeric"],
                "type" => ["required", Rule::in(["Single-family home", "Duplex", "Triplex", "Fourplex", "Condominium", "Townhouse", "Apartment building", "Co-op", "Manufactured home", "Tiny home", "Office building", "Warehouse"])],
                "images" => ["nullable", "array"],
                "images.*" => ["nullable", "string", "max:2048"],
            ]);

            return $next($request);
        } catch (\Exception $e) {
            return response()->json([
                "type" => "Invalid request",
                "message" => "Request data is invalid",
                "errors" => $e->errors()
            ], 422);
        }
    }
}