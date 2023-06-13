<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\V1\AuthController;
use App\Http\Controllers\Admin\V1\UserController;

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

// --- Authentication
Route::post('/login',                           [AuthController::class, 'login']);
Route::get('/logout',                           [AuthController::class, 'logout']);

// --- User
Route::post('/signup',                          [UserController::class, 'store']);         
Route::get('/getSelf',                          [UserController::class, 'getSelf']);

