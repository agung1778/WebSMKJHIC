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

// Endpoint pelacakan traffic (publik, tanpa CSRF karena dipanggil via sendBeacon/fetch)
Route::post('/traffic/track/page', [TrafficController::class, 'trackPage'])->name('traffic.track.page');
Route::post('/traffic/track/click', [TrafficController::class, 'trackClick'])->name('traffic.track.click');
