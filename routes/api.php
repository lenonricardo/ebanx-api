<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;

Route::get('balance', [AccountController::class, 'balance']);
Route::post('event', [AccountController::class, 'event']);
Route::post('reset', [AccountController::class, 'reset']);