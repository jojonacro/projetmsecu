<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\Api\PassagerController;

Route::post('/passagers', [PassagerController::class, 'store']);
Route::get('/passagers/search', [PassagerController::class, 'search']);
