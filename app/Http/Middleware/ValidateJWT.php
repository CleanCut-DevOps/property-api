<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class ValidateJWT
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
            $request->user_id = JWTAuth::getPayload(JWTAuth::getToken())['sub'];
            return $next($request);
        } catch (\Exception $e) {
            if (request()->header("Authorization")) {
                return response()->json([
                    "type" => "Unauthorized",
                    "message" => "Invalid authorization token"
                ], 401);
            } else {
                return response()->json([
                    "type" => "Unauthorized",
                    "message" => "This route requires authentication"
                ], 401);
            }
        }
    }
}
