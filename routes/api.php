<?php

use App\Http\Controllers\AuthenticationController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix("v1")->group(function () {

    # Authentication Routes
    Route::post("/auth/register", [AuthenticationController::class, 'register']);
    Route::post("/auth/login",[AuthenticationController::class,"login"]);


    # Post routes
});
