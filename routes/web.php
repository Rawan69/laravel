<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TempController;
use App\Models\Temp;

Route::get('/', function () {
    return view('welcome');
});


Route::post('login', [UserController::class, 'login']);


Route::post('register', [UserController::class, 'register']);


Route::post('logout', [UserController::class, 'logout']);


Route::get('/login', function () {
    return view('auth.login');
});

Route::get('/register', function () {
    return view('auth.register');
});

Route::post('register', [UserController::class, 'register']);

Route::get('/upload', function () {
    return view('layouts.upload_images');
});

Route::post('/upload-image', [TempController::class, 'store'])->name('upload.image');
Route::get('/user-images', [TempController::class, 'getUserImages'])->name('user.images');
Route::delete('/delete-image/{id}', [TempController::class, 'deleteImage'])->name('delete.image');


Route::get('/treatments', function () {
    return view('treatments'); 
})->middleware('api'); 

