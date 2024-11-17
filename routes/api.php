<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\HobbyController;

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

// Auth routes
Route::post('login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    // Auth routes
    Route::post('logout', [AuthController::class, 'logout']);

    // Routes for Admin role
    Route::middleware(['role:admin'])->group(function () {
        Route::get('users', [UserController::class, 'index']);
        Route::post('users', [UserController::class, 'store']);
        Route::post('users/{id}', [UserController::class, 'update']);
        Route::get('users/{id}', [UserController::class, 'show']);
        Route::delete('users/{id}', [UserController::class, 'destroy']);
        Route::put('users/{id}/status', [UserController::class, 'changeStatus']);
    });

    // Routes for Admin & User role
    Route::middleware(['role:admin|user'])->prefix('hobby')->group(function () {
        Route::post('save', [HobbyController::class, 'save']);
        Route::get('users/{id}', [UserController::class, 'show']);
    });
});
