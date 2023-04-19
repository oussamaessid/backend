<?php

use App\Http\Controllers\HotelController;
use App\Http\Controllers\PaysController;
use App\Http\Controllers\PersonneController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PlatsController;
use App\Http\Controllers\PlansController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/hotels', [HotelController::class, 'index']);
Route::post('/add-hotel', [HotelController::class, 'store']);
Route::get('/edit-hotel/{id}', [HotelController::class, 'edit']);
Route::put('/update-hotel/{id}', [HotelController::class, 'update']);
Route::delete('/delete-hotel/{id}', [HotelController::class, 'delete']);

Route::post('/add-user', [PersonneController::class, 'store']);
Route::get('/edit-user/{id}', [PersonneController::class, 'edit']);
Route::put('/update-user/{id}', [PersonneController::class, 'update']);
Route::delete('/delete-user/{id}', [PersonneController::class, 'delete']);
Route::put('/valider-user/{id}', [PersonneController::class, 'valider']);
Route::put('/désactiver-user/{id}', [PersonneController::class, 'désactiver']);

//routes of table service
Route::get('/services', [ServiceController::class, 'index']);
Route::get('/services/{id_hotel}', [ServiceController::class, 'getservicesbyidhotel']);
Route::get('/searchS/{nom}', [ServiceController::class, 'searchByName']);
//table menu
Route::get('/menu/{id_service}', [MenuController::class, 'getmenubyidservice']);
//table plats
Route::get('/plats/{id}', [PlatsController::class, 'get_plat_by_idmenu']);
Route::get('/categories/{id}', [PlatsController::class, 'get_categorie_by_idmenu']);
//table plans
Route::get('/plans/{id_service}', [PlansController::class, 'getplansbyidservice']);


Route::get('/pays', [PaysController::class, 'index']);

Route::middleware('jwt.auth')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'middleware' => 'api', 'prefix' => 'auth',
    'namespace' => 'App\Http\Controllers',
], function ($router) {


    Route::post('/createUser', [PersonneController::class, 'register']);
    Route::post('/loginUser', [PersonneController::class, 'login']);
    Route::get('/users', [PersonneController::class, 'index']);
    Route::get('/profile', [PersonneController::class, 'profile']);
    Route::post('/logout', [PersonneController::class, 'logout']);
});
