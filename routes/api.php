<?php

use App\Http\Controllers\ImageController;
use App\Http\Controllers\PropertyController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Catch-all route
Route::fallback(function () {
    return response()->json([
        "type" => "Not found",
        "message" => "There's nothing here.."
    ], 404);
});

Route::get('/', [PropertyController::class, 'index']);
Route::post('/', [PropertyController::class, 'store']);

Route::get('/{id}', [PropertyController::class, 'show']);
Route::put('/{id}', [PropertyController::class, 'update']);
Route::delete('/{id}', [PropertyController::class, 'destroy']);

Route::post('/{id}/image', [ImageController::class, 'store']);
Route::delete('/{id}/image', [ImageController::class, 'destroy']);
