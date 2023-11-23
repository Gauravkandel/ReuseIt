<?php

use App\Http\Controllers\AuthController;
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
    Route::get('/filter', 'filter');
});
Route::controller(SellProductController::class)->group(function () { //for posting products
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
