<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('login', [AuthController::class,'login'])->name('login');

Route::middleware(['auth:sanctum','dynamic-database'])->group(function(){
    Route::post('logout',[AuthController::class,'logout'])->name('logout');
    Route::resource('products', ProductController::class);
});