<?php

use App\Http\Controllers\PropertyController;
use App\Http\Middleware\ValidateJWT;
use Illuminate\Http\Request;
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

Route::get('/', function () {
    return response()->json([
        "type" => "Not found",
        "message" => "There's nothing here..",
    ], 404);
});

Route::get('/property', [PropertyController::class, 'index']);
Route::get('/property/{id}', [PropertyController::class, 'show']);
Route::post('/property', [PropertyController::class, 'store']);
Route::put('/property/{id}', [PropertyController::class, 'update']);
Route::delete('/property/{id}', [PropertyController::class, 'destroy']);
