<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\API\V1\Tasks\Controllers\TaskController;
use Modules\API\V1\Auth\Controllers\AuthController;

Route::group(['prefix' => 'v1', 'middleware' => 'throttle:350,1'], function () {
    Route::post('/register', [AuthController::class, 'register'])->name('api.v1.register');
    Route::post('/login', [AuthController::class, 'login'])->name('api.v1.login');
});

Route::group(['prefix' => 'v1', 'middleware' => ['throttle:350,1', 'auth:sanctum']], function () {
    Route::resource('tasks', TaskController::class)->names('v1.tasks');
    Route::post('/logout', [AuthController::class, 'logout'])->name('v1.logout');

});
