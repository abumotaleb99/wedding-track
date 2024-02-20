<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WeddingTrackController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/add-guest', [WeddingTrackController::class, 'addGuestInvitation']);

Route::get('/check-in/list', [WeddingTrackController::class, 'getCheckInList']);
Route::get('/guest/{id}', [WeddingTrackController::class, 'getInvitedGuestById']);
Route::post('/check-in', [WeddingTrackController::class, 'checkIn']);