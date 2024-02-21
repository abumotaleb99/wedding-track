<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WeddingTrackController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [WeddingTrackController::class, 'showCheckInList']);
Route::get('/guest-invitations', [WeddingTrackController::class, 'showGuestInvitationsPage']);
Route::get('/guest/delete/{id}', [WeddingTrackController::class, 'deleteGuest']);