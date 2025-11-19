<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\Api\PassagerController;
use App\Http\Controllers\VoyageController;

Route::post('/passagers', [PassagerController::class, 'store']);
Route::get('/passagers/search', [PassagerController::class, 'search']);
Route::get('/voyages/{code}', [VoyageController::class, 'show']); // test du QR code

