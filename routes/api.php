<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\SellerController;

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

Route::prefix('/clients')->group(function (){
    Route::post('/', [ClientController::class, 'create']);
    Route::get('/', [ClientController::class, 'getAll']);
    Route::get('/{clientUUID}', [ClientController::class, 'find']);
    Route::delete('/{clientUUID}', [ClientController::class, 'delete']);
    Route::patch('/{clientUUID}/accredited', [ClientController::class, 'changeAccreditedStatus']);
});

Route::prefix('/sellers')->group(function (){
    Route::post('/', [SellerController::class, 'create']);
    Route::get('/', [SellerController::class, 'getAll']);
    Route::get('/{sellerUUID}', [SellerController::class, 'find']);
    Route::delete('/{sellerUUID}', [SellerController::class, 'delete']);
});