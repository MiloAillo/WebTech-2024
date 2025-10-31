<?php

use App\Http\Controllers\AccessController;
use App\Http\Controllers\AdminController;
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
            Route::post("/signout", []);
        });
        Route::post("/signup", [AccessController::class, "signup"]);
        Route::post("/signin", [AccessController::class, "login"]);
        Route::middleware('auth:sanctum')->post("/signout", [AccessController::class, "logout"]);
    });

    Route::middleware('auth:sanctum')->get("/admins", [AdminController::class, "getAdmins"]);
    Route::middleware('auth:sanctum')->get('/users', [AdminController::class, "getUsers"]);
    Route::middleware('auth:sanctum')->get('/users/{username}', [AdminController::class, "getUser"]);
    Route::middleware('auth:sanctum')->put('/users/{id}', [AdminController::class, "updateUser"]);
    Route::middleware('auth:sanctum')->delete('/users/{id}', [AdminController::class, "deleteUser"]);
    Route::fallback(function() {
        return response()->json([
            "status" => "invalid",
            "message" => "not found"
        ], 404);
    });
});