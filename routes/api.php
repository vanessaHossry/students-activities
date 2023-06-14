<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Controllers\Middleware;
use App\Http\Controllers\Admin\V1\AdminController;
use App\Http\Controllers\Client\V1\UserController;
use App\Http\Controllers\Admin\V1\AdminAuthController;
use App\Http\Controllers\Client\V1\UserAuthController;

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


Route::group(
    [
        'middleware' => ['api', 'auth.apikey'],
        'prefix' => 'admin/v1'
    ],
    function () {
        // --- Admin Authentication
        Route::post('/login',                       [AdminAuthController::class, 'login']);
        Route::get('/logout',                       [AdminAuthController::class, 'logout']);

        // --- Admin
        Route::get('/getSelf',                      [AdminController::class, 'getSelf']);

    }
);

Route::group(
    [
        'middleware' => ['api', 'auth.apikey'],
        'prefix' => 'client/v1'
    ],
    function () {
       // --- User Authentication 
       Route::post('/login',                       [UserAuthController::class, 'login']);
       Route::get('/logout',                       [UserAuthController::class , 'logout']);

       // --- User
       Route::get('/getSelf',                      [UserController::class, 'getSelf']);
       Route::post('/store',                       [UserController::class , 'store']);
      
    }
);