<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BoardController;
use App\Http\Controllers\Api\CardController;
use App\Http\Controllers\Api\ColumnController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('boards', BoardController::class);

    Route::post('boards/{board}/columns', [ColumnController::class, 'store']);
    Route::put('columns/{column}', [ColumnController::class, 'update']);
    Route::delete('columns/{column}', [ColumnController::class, 'destroy']);

    Route::post('columns/{column}/cards', [CardController::class, 'store']);
    Route::get('cards/{card}', [CardController::class, 'show']);
    Route::put('cards/{card}', [CardController::class, 'update']);
    Route::delete('cards/{card}', [CardController::class, 'destroy']);
});
