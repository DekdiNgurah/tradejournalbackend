<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TradeController;
use App\Http\Controllers\TradeImageController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// PUBLIC ROUTES (TAMU)
Route::post('/register', [AuthController::class, 'register']);
// 💡 FIX 1: Tambahkan ->name('login') di ujung route login
Route::post('/login', [AuthController::class, 'login'])->name('login');

// PROTECTED ROUTES (WAJIB LOGIN)
Route::middleware('auth:sanctum')->group(function () {
    // Route::get('/user', function (Request $request) {
    //     return response()->json($request->user());
    // });

    Route::get('/user', [ProfileController::class, 'show']);

    Route::put('/user', [ProfileController::class, 'update']);

    Route::put('/user/password', [ProfileController::class, 'updatePassword']);
    
    Route::get('/profile', [AuthController::class, 'profile']);
    
    // 💡 FIX 2: Ubah GET menjadi POST untuk logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // Trade & Dashboard Routes
    Route::apiResource('trades', TradeController::class);
    Route::post('/trades/{id}/images', [TradeImageController::class, 'store']);
    Route::delete('/trades-images/{id}', [TradeImageController::class, 'destroy']);

    Route::get('/dashboard/summary', [DashboardController::class, 'summary']);
    Route::get('/calendar/pnl', [CalendarController::class, 'pnl']);
});
