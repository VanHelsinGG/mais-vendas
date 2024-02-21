<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;

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

Route::post('/clients', [ClientController::class, 'create']);
Route::get('/clients', [ClientController::class, 'getAll']);
Route::get('/clients/{clientsUUID}', [ClientController::class, 'find']);
Route::delete('/clients/{clientsUUID}', [ClientController::class, 'delete']);
Route::patch('/clients/{clientsUUID}/accredited', [ClientController::class, 'changeAccreditedStatus']);