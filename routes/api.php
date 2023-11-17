<?php

use App\Http\Controllers\SellProductController;
use App\Http\Controllers\ViewProductController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::controller(ViewProductController::class)->group(function () { //for viewing products
    Route::get('/getIndivProduct/{id}', 'getIndivProduct');
    Route::get('/getdat', 'fetchalldata');
});
Route::controller(SellProductController::class)->group(function () { //for posting products
    Route::post('/homeappliances', 'HomeAppliances');
    Route::post('/electronics', 'Electronics');
    Route::post('/furnitures', 'Furnitures');
});
