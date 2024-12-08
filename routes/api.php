<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PlantController;
use App\Http\Controllers\DiseaseController;
use App\Http\Controllers\TreatmentController;


// Route::post('/tokens/create', function (Request $request) {
//     $request->validate([
//         'token_name' => 'required|string|max:255',
//     ]);

//     $token = $request->user()->createToken($request->token_name);

//     return ['token' => $token->plainTextToken];

  

// });

// Route::middleware(['jwt.auth'])->get('/user', function () {
//     return response()->json(auth()->user());
// });



//  Route::middleware('auth:api')->get('/user', function (Request $request) {
//      return $request->user();
//  });




// Route::prefix('user')->group(function () {
//    Route::post('/register', [UserController::class, 'register']);
//    Route::post('/login', [UserController::class, 'login']);
//    Route::middleware('auth:api')->post('/logout', [UserController::class, 'logout']);


// });



Route::resource('plants', PlantController::class)->except(['create' , 'edit']);
Route::resource('treatments', TreatmentController::class)->except(['create' , 'edit']);


Route::get('/diseases', [DiseaseController::class, 'index']);
Route::post('/diseases', [DiseaseController::class, 'store']);
Route::delete('diseases/{id}', [DiseaseController::class, 'destroy']);
Route::get('/diseases/search', [DiseaseController::class, 'search']);


 
// //global auth for API routes
// Route::middleware('auth:sanctum')-> group(function(){ 
//    Route::get('/user',function(Request $request){

//     return $request->user();
// });

// //admins routes
// Route::middleware(['checkstatus:admin'])->group(function () {
//     Route::get('/admin', function () {
//         return response()->json(['message' => 'Welcome Admin!']);
//     });
// });
// });


Route::group([
    'middleware' => 'api',
    'prefix' => 'users'
], function ($router) {
    Route::post('/register', [userController::class, 'register'])->name('register');
    Route::post('/login', [userController::class, 'login'])->name('login');
    Route::post('/logout', [userController::class, 'logout'])->middleware('auth:api')->name('logout');
    Route::post('/refresh', [userController::class, 'refresh'])->middleware('auth:api')->name('refresh');
    Route::post('/me', [userController::class, 'me'])->middleware('auth:api')->name('me');
});
Route::middleware('auth:api')->get('/user', [UserController::class, 'getUserByToken']);