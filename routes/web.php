<?php

use App\Http\Controllers\AccommodationController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoomTypeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Rutas de recursos
Route::resource('hotels', HotelController::class);
Route::resource('room-types', RoomTypeController::class);
Route::resource('accommodations', AccommodationController::class);