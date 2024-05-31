<?php

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DeliveryOrderController;


Route::get('/', [LoginController::class, "index"])->name("login");
Route::post('/', [LoginController::class, "checkLogin"]);
Route::post('/logout', [LoginController::class, "logout"])->middleware("auth");



Route::get('/dashboard', function () {
    return view("pages.dashboard");
})->middleware("auth");

//show data
Route::get('/deliveryorder', [DeliveryOrderController::class, "index"] );

//create new data
Route::get('/Order/upload', [DeliveryOrderController::class, "create"] );

Route::post('/Order/upload', [DeliveryOrderController::class, "store"] );
//edit data
Route::get('/Order/{id}/edit', [DeliveryOrderController::class, "edit"] );

Route::post('/Order/{id}/edit', [DeliveryOrderController::class, "update"] );
//delete data
Route::post('/Order/{id}', [DeliveryOrderController::class, "destroy"] );
