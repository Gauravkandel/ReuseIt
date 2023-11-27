<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\SellProductController;
use App\Http\Controllers\ViewProductController;
use Illuminate\Support\Facades\Route;

//recommendation
Route::post('/recommend', [RecommendationController::class, 'recommend']);
Route::post('/get_recommend', [RecommendationController::class, 'getrecommended']);

//for viewing products
Route::controller(ViewProductController::class)->group(function () {
    Route::get('/getIndivProduct/{id}', 'getIndivProduct');
    Route::get('/getdat', 'fetchalldata');
    Route::get('/filter', 'filter');
    Route::get('/search', 'search');
});

//for posting products
Route::controller(SellProductController::class)->group(function () {
    Route::post('/homeappliances', 'HomeAppliances');
    Route::post('/electronics', 'Electronics');
    Route::post('/furnitures', 'Furnitures');
    Route::post('/clothings', 'Clothing');
    Route::post('/sports', 'Sports');
    Route::post('/books', 'Books');
    Route::post('/antiques', 'Antiques');
    Route::post('/cars', 'Cars');
    Route::post('/motorcycles', 'Motorcycle');
    Route::post('/scooters', 'Scooter');
    Route::post('/bicycles', 'Bicycle');
    Route::post('/toys', 'Toys');
    Route::post('/music', 'Music');
});

//authentication
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::group(['middleware' => 'api'], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::post('/me', [AuthController::class, 'me']);
});
