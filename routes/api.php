<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RequestAnswerController;
use App\Http\Controllers\RequestController;
use Illuminate\Support\Facades\Route;

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

Route::post('/login', [AuthController::class, 'store']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('requests', RequestController::class)
        ->except(['update', 'destroy']);

    Route::post('request-answers/{id}', RequestAnswerController::class);
});
