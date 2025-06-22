<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\InstrumentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(['message' => 'Hello world!']);
});

Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register')->name('register');
    Route::post('/login', 'login')->name('login');
    Route::get('/health', 'healthCheck')->name('health');
});

Route::apiResources([
    'instruments' => InstrumentController::class,
]);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::get('/me', [AuthController::class, 'me'])->name('me');
    Route::get('/auth/health', function () {
        return response()->json(['status' => 'OK']);
    })->name('auth.health');
    Route::get('/instruments-all', [InstrumentController::class, 'index'])->name('instruments.all');
});
