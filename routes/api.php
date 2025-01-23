<?php

use App\Http\Controllers\PropertyController;
use App\Http\Controllers\PropertyTypeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserRoleController;
use App\Http\Middleware\Admin;
use App\Http\Middleware\Landlord;
use Illuminate\Support\Facades\Route;

// PUBLIC ROUTES
// Health
Route::get('health', function () {
    return response()->json([
        'message' => 'API is up and running.',
    ]);
});
// User
Route::controller(UserController::class)->group(function () {
    Route::post('login', 'login')->middleware('throttle:4,1');
    Route::post('register', 'store')->middleware('throttle:2,1');
});
// User Role
Route::controller(UserRoleController::class)->group(function () {
    Route::get('role', 'index');
});
// Property Type
Route::controller(PropertyTypeController::class)->group(function () {
    Route::get('property-type', 'index');
});

// PROTECTED ROUTES
Route::middleware('auth:sanctum')->group(function () {
    // User
    Route::controller(UserController::class)->group(function () {
        Route::post('logout', 'logout');
        Route::get('verify', 'verify');
        Route::put('user/{id}', 'update');
    });
    // Property
    Route::controller(PropertyController::class)->group(function () {
        Route::get('property', 'index');
        Route::get('property/{id}', 'show');
    });

    // ADMIN AND LANDLORD ROUTES
    Route::middleware(Landlord::class)->group(function () {
        // Property
        Route::controller(PropertyController::class)->group(function () {
            Route::post('property', 'store');
            Route::post('property/{id}', 'update');
            Route::delete('property/{id}', 'delete');
        });
    });

    // ADMIN ONLY ROUTES
    Route::middleware(Admin::class)->group(function () {
        // User
        Route::controller(UserController::class)->group(function () {
            Route::post('user', 'store');
            Route::get('user', 'index');
            Route::get('user/{id}', 'show');
            Route::delete('user/{id}', 'delete');
        });
    });
});


