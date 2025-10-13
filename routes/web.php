<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $response = [
        "app" => "laravel-blog-api",
        "developer" => "ThinkCode IT Solutions",
        "version" => "v1",
        "status" => "healthy"
    ];
    return response()->json($response);
});
