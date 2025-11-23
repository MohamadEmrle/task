<?php

use App\Http\Controllers\LeadController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\StatusController;
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
Route::post('login', [LoginController::class, 'login']);
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/leads', [LeadController::class, 'index']);
    Route::get('/statuses', [StatusController::class, 'index']);
});
