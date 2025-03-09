<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PlantController;
use App\Http\Controllers\DiseaseController;
use App\Http\Controllers\TreatmentController;
use App\Http\Controllers\Dht22SensorController;
use App\Http\Controllers\TempController;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\SoilMoistureController;
use App\Http\Controllers\WaterLevelController;
use App\Http\Controllers\ActivityController;

Route::get('/activities', [ActivityController::class, 'index']);
Route::post('/activities', [ActivityController::class, 'store']);
Route::get('/activities/{id}', [ActivityController::class, 'show']);
Route::delete('/activities/{id}', [ActivityController::class, 'destroy']);


Route::get('/water-levels', [WaterLevelController::class, 'index']);
Route::post('/water-levels', [WaterLevelController::class, 'store']);
Route::get('/water-levels/{id}', [WaterLevelController::class, 'show']);
Route::delete('/water-levels/{id}', [WaterLevelController::class, 'destroy']);




Route::get('/soil-moistures', [SoilMoistureController::class, 'index']);
Route::post('/soil-moistures', [SoilMoistureController::class, 'store']);
Route::get('/soil-moistures/{id}', [SoilMoistureController::class, 'show']);
Route::delete('/soil-moistures/{id}', [SoilMoistureController::class, 'destroy']);


Route::resource('plants', PlantController::class)->except(['create' , 'edit']);
Route::resource('treatments', TreatmentController::class)->except(['create' , 'edit']);
Route::resource('sensors', Dht22SensorController::class);


Route::get('/diseases', [DiseaseController::class, 'index']);
Route::post('/diseases', [DiseaseController::class, 'store']);
Route::delete('diseases/{id}', [DiseaseController::class, 'destroy']);
Route::get('/diseases/search', [DiseaseController::class, 'search']);


// Route::middleware('auth:api')->group(function () {
    Route::post('upload-image', [TempController::class, 'store']);
    Route::get('show_image',[TempController::class,'index']);
//     Route::get('/user-images', [TempController::class, 'getUserImages']); 
//     Route::get('/get-image/{filename}', [TempController::class, 'getImage']); 
//     Route::delete('/delete-image/{filename}', [TempController::class, 'deleteImage']); 
// // });


Route::post('/plants', [PlantController::class, 'store']);


Route::group([
    'middleware' => 'api',
    'prefix' => 'users'
], function ($router) {
    Route::post('/register', [userController::class, 'register'])->name('register');
    Route::post('/login', [userController::class, 'login'])->name('login');
 
    Route::post('/logout', [userController::class, 'logout'])->middleware('auth:api')->name('logout');
    Route::post('/refresh', [userController::class, 'refresh'])->middleware('auth:api')->name('refresh');
    Route::post('/me', [userController::class, 'me'])->middleware('auth:api')->name('me');
    Route::get('/search', [UserController::class, 'getUserByToken'])->middleware('auth:api')->name('search');
});
