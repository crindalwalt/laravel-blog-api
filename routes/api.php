<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\Authentication2;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix("v1")->group(function () {

    # Authentication Routes
    Route::post("/auth/register", [AuthenticationController::class, 'register']);
    Route::post("/auth/login",[AuthenticationController::class,"login"]);


    # Post routes
});

Route::prefix("v2")->group(function(){

//    Authentication Routes By Ashir
    Route::post("/auth/register",[Authentication2::class,'registerV2']);
    Route::post('/auth/login',[Authentication2::class,'loginV2']);

});
