<?php

namespace App\Http\Middleware;

use App\Models\Property;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BelongsToAccount
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (JSONResponse|RedirectResponse|Response) $next
     * @return JSONResponse|RedirectResponse|Response
     */
    public function handle(Request $request, Closure $next): JSONResponse|RedirectResponse|Response
    {
        $requestedProperty = Property::whereId(request('id'));

        if ($requestedProperty->exists() < 1) {
            return response()->json([
                'type' => 'Not found',
                'message' => 'Property not found'
            ], 404);

        } else if ($requestedProperty->first()->user_id !== $request->user_id) {
            return response()->json([
                'type' => 'Unauthorized',
                'message' => 'Property does not belong to user'
            ], 403);

        }

        return $next($request);
    }
}
