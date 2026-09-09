<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrafficController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Traffic tracking routes
Route::post('/traffic/track/page', [TrafficController::class, 'trackPage']);
Route::post('/traffic/track/click', [TrafficController::class, 'trackClick']);

// Rate limiter for traffic tracking will be applied in the controller middleware
