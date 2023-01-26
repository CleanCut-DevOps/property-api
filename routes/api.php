<?php

use App\Http\Controllers\ImageController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\TypeController;
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
Route::post('/property', [PropertyController::class, 'store']);

Route::get('/property/{id}', [PropertyController::class, 'show']);
Route::put('/property/{id}', [PropertyController::class, 'update']);;
Route::put('/property/{id}/address', [PropertyController::class, 'updateAddress']);
Route::put('/property/{id}/type', [PropertyController::class, 'updateType']);
Route::put('/property/{id}/rooms', [PropertyController::class, 'updateRooms']);
Route::delete('/property/{id}', [PropertyController::class, 'destroy']);

Route::post('/image/add', [ImageController::class, 'store']);
Route::post('/image/remove', [ImageController::class, 'destroy']);

Route::get('/type/property', [TypeController::class, 'indexProperty']);
Route::get('/type/property/{id}', [TypeController::class, 'showProperty']);
//Route::post('/type/property', [TypeController::class, 'storeProperty']);
//Route::put('/type/property/{id}', [TypeController::class, 'updateProperty']);
//Route::delete('/type/property/{id}', [TypeController::class, 'destroyProperty']);

Route::get('/type/property/{id}/rooms', [TypeController::class, 'showPropertyRooms']);

Route::get('/type/room/{id}', [TypeController::class, 'showRoom']);
//Route::post('/type/room', [TypeController::class, 'storeRoom']);
//Route::put('/type/room/{id}', [TypeController::class, 'updateRoom']);
//Route::delete('/type/room/{id}', [TypeController::class, 'destroyRoom']);
