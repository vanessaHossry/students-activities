<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Controllers\Middleware;
use App\Http\Controllers\Admin\V1\ActivitiesController;
use App\Http\Controllers\Admin\V1\AuthController        as V1AdminAuthController;
use App\Http\Controllers\Admin\V1\UserController        as V1AdminUserController;
use App\Http\Controllers\Client\V1\AuthController       as V1ClientAuthController;
use App\Http\Controllers\Client\V1\UserController       as V1ClientUserController;


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
        Route::post('/login',                                       [V1AdminAuthController::class, 'login']);
        Route::get('/logout',                                       [V1AdminAuthController::class, 'logout']);
        Route::post('/refresh',                                     [V1AdminAuthController::class, 'refresh']);
                        
                        
        // --- Admin                
        Route::get('/getSelf',                                      [V1AdminUserController::class, 'getSelf']);
        Route::post('/store',                                       [V1AdminUserController::class, 'store']);
        Route::get('/index',                                        [V1AdminUserController::class, 'index']);
        Route::get('/show/{email}',                                 [V1AdminUserController::class, 'show']);
        Route::delete('/destroy/{email}',                           [V1AdminUserController::class, 'destroy']);
        Route::get('/getDeleted',                                   [V1AdminUserController::class, 'getDeleted']);

        // --- Activities
        Route::post('/storeActivityWeek',                           [ActivitiesController::class,'store']);  
        Route::put('/update-activity-week/{activity_slug}',         [ActivitiesController::class,'update']);             
    }
);

Route::group(
    [
        'middleware' => ['api', 'auth.apikey'],
        'prefix' => 'client/v1'
    ],
    function () {
       // --- User Authentication 
       Route::post('/login',                       [V1ClientAuthController::class, 'login']);
       Route::get('/logout',                       [V1ClientAuthController::class , 'logout']);

       // --- User
       Route::get('/getSelf',                      [V1ClientUserController::class, 'getSelf']);
       Route::post('/signUp',                      [V1ClientUserController::class , 'signUp']);
       Route::post('/forgotPassword',              [V1ClientUserController::class, 'forgotPassword']);
       Route::post('/resetPassword',               [V1ClientUserController::Class, 'resetPassword']);

       
       
      
    }
);