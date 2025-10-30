<?php

use App\Http\Controllers\AccessController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix("v1")->group(function() {
    Route::prefix("auth")->group(function() {
        Route::prefix("admin")->group(function() {
            Route::post("/signup", [AccessController::class, "adminSignup"]);
            Route::post("/signin", [AccessController::class, "adminLogin"]);
        });
        Route::post("/signup", [AccessController::class, "signup"]);
        Route::post("/signin", [AccessController::class, "login"]);
    });
});