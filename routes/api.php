<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Controllers\Middleware;
use App\Http\Controllers\admin\v1\RoleController;
use App\Http\Controllers\admin\v1\ActivityController;
use App\Http\Controllers\client\v1\ActivitiesWeekdaysController    as V1ClientActivitiesWeekdaysController;
use App\Http\Controllers\Admin\V1\AuthController        as V1AdminAuthController;
use App\Http\Controllers\Admin\V1\UserController        as V1AdminUserController;
use App\Http\Controllers\Client\V1\AuthController       as V1ClientAuthController;
use App\Http\Controllers\Client\V1\UserController       as V1ClientUserController;
use App\Http\Controllers\Admin\V1\ActivitiesWeekdaysController      as V1AdminActivitiesWeekdaysController;


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
        Route::get('/portal-count-users',                           [V1AdminUserController::class, 'getPortalsUserCount']);

        // --- Activity Weekdays
        Route::post('/store-activity-week',                         [V1AdminActivitiesWeekdaysController::class,'store']);  
        Route::put('/update-activity-week/{activity_slug}',         [V1AdminActivitiesWeekdaysController::class,'update']); 
        Route::put('/delete-activity-week/{activity_slug}',         [V1AdminActivitiesWeekdaysController::class,'destroy']); 

        // --- Activity
        Route::post('/store-activity',                              [ActivityController::class, 'store']);
        Route::get('/get-activities',                               [ActivityController::class, 'index']);
        Route::get('/get-activity-by-slug/{activity_slug}',         [ActivityController::class, 'show']);
        Route::put('/update-activity-price/{activity_slug}',        [ActivityController::class, 'update']);
        Route::delete('/delete-activity/{activity_slug}',           [ActivityController::class, 'destroy']);

         // --- role
        Route::get('/get-roles',                                    [RoleController::class, 'index']);
        Route::patch('/user/{user_email}/permission/{permission}',  [RoleController::class, 'givePermissionToUser']);

    }
);

Route::group(
    [
        'middleware' => ['api', 'auth.apikey'],
        'prefix' => 'client/v1'
    ],
    function () {
       // --- User Authentication 
       Route::post('/login',                                        [V1ClientAuthController::class, 'login']);
       Route::get('/logout',                                        [V1ClientAuthController::class , 'logout']);
       Route::post('/refresh',                                      [V1ClientAuthController::class, 'refresh']);
                
       // --- User                   
       Route::get('/getSelf',                                       [V1ClientUserController::class, 'getSelf']);
       Route::post('/signUp',                                       [V1ClientUserController::class , 'signUp']);
       Route::post('/forgotPassword',                               [V1ClientUserController::class, 'forgotPassword']);
       Route::post('/resetPassword',                                [V1ClientUserController::Class, 'resetPassword']);

       // --- activity
       Route::get('/get-activities',                                [ActivityController::class, 'index']);
       Route::get('/get-activity-by-slug/{activity_slug}',          [ActivityController::class, 'show']);

       // --- activity weekdays
       Route::get('/get-activities-weekdays',                       [V1ClientActivitiesWeekdaysController::class, 'index']);

      
       }
);