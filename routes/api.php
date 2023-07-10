<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Controllers\Middleware;
use App\Http\Controllers\admin\v1\RoleController;
use App\Http\Controllers\admin\v1\ProductController;
use App\Http\Controllers\Admin\V1\AuthController        as V1AdminAuthController;
use App\Http\Controllers\Admin\V1\UserController        as V1AdminUserController;
use App\Http\Controllers\Client\V1\AuthController       as V1ClientAuthController;
use App\Http\Controllers\Client\V1\UserController       as V1ClientUserController;
use App\Http\Controllers\admin\v1\ActivityController  as V1AdminActivityController;
use App\Http\Controllers\client\v1\ActivityController  as V1ClientActivityController;
use App\Http\Controllers\Admin\V1\ActivitiesWeekdaysController      as V1AdminActivitiesWeekdaysController;
use App\Http\Controllers\client\v1\ActivitiesWeekdaysController    as V1ClientActivitiesWeekdaysController;


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
        Route::post('/store-activity',                              [V1AdminActivityController::class, 'store']);
        Route::get('/get-activities',                               [V1AdminActivityController::class, 'index']);
        Route::get('/get-activity-by-slug/{activity_slug}',         [V1AdminActivityController::class, 'show']);
        Route::put('/update-activity-price/{activity_slug}',        [V1AdminActivityController::class, 'update']);
        Route::delete('/delete-activity/{activity_slug}',           [V1AdminActivityController::class, 'destroy']);
        Route::patch('/restore-activity/{activity_slug}',           [V1AdminActivityController::class, 'restore']);
        Route::patch('/deactivate-activity/{activity_slug}',        [V1AdminActivityController::class, 'deactivate']);
        Route::patch('/activate-activity/{activity_slug}',          [V1AdminActivityController::class, 'activate']);

        // --- product
        Route::post('/store-product',                               [ProductController::class, 'store']);
        Route::put('/update-product-language/{product_slug}',       [ProductController::class, 'updateTranslation']);

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
       Route::post('/update-images',                                [V1ClientUserController::class,  'updateImages']);
       Route::post('/forgotPassword',                               [V1ClientUserController::class, 'forgotPassword']);
       Route::post('/resetPassword',                                [V1ClientUserController::Class, 'resetPassword']);

       // --- activity
       Route::get('/get-activities',                                [V1ClientActivityController::class, 'index']);
       Route::get('/get-activity-by-slug/{activity_slug}',          [V1ClientActivityController::class, 'show']);

       // --- activity weekdays
       Route::get('/get-activities-weekdays',                       [V1ClientActivitiesWeekdaysController::class, 'index']);
       Route::get('/filter-activities-weekdays',                    [V1ClientActivitiesWeekdaysController::class, 'show']);
      
       }
);