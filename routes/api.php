<?php

use App\Http\Controllers\PropertyController;
use App\Http\Controllers\PropertyTypeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserRoleController;
use App\Http\Middleware\Admin;
use App\Http\Middleware\Landlord;
use Illuminate\Support\Facades\Route;

// PUBLIC ROUTES
// User
Route::controller(UserController::class)->group(function () {
    Route::post('login', 'login')->middleware('throttle:4,1');
    Route::post('user', 'store')->middleware('throttle:2,1');
});

Route::controller(UserRoleController::class)->group(function () {
    Route::get('role', 'index');
});

// PROTECTED ROUTES
Route::middleware('auth:sanctum')->group(function () {
    // User
    Route::controller(UserController::class)->group(function () {
        Route::post('logout', 'logout');
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
            Route::put('property/{id}', 'update');
            Route::delete('property/{id}', 'delete');
        });
    });

    // ADMIN ONLY ROUTES
    Route::middleware(Admin::class)->group(function () {
        // User
        Route::controller(UserController::class)->group(function () {
            Route::get('user', 'index');
            Route::get('user/{id}', 'show');
            Route::put('user/{id}', 'update');
            Route::delete('user/{id}', 'delete');
        });

        // User Role
        Route::controller(UserRoleController::class)->group(function () {
            Route::get('role/{id}', 'show');
            Route::post('role', 'store');
            Route::put('role/{id}', 'update');
            Route::delete('role/{id}', 'delete');
        });

        // Property Type
        Route::controller(PropertyTypeController::class)->group(function () {
            Route::get('property-type', 'index');
            Route::get('property-type/{id}', 'show');
            Route::post('property-type', 'store');
            Route::put('property-type/{id}', 'update');
            Route::delete('property-type/{id}', 'delete');
        });
    });
});


