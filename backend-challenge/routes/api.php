<?php

use App\Http\Controllers\AmortizationsController;
use App\Http\Controllers\PaymentsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectsController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Projects
Route::post('/projects', [ProjectsController::class, 'store']);

// Amortizations
Route::get('/amortizations', [AmortizationsController::class, 'index']);
Route::post('/amortizations/{amortization}', [AmortizationsController::class, 'show']);

// Payments
Route::get('/payments', [PaymentsController::class, 'index']);
