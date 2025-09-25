<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\AboutHomeController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\HeroBannerController;
use App\Http\Controllers\LocationsController;
use App\Http\Controllers\MilestoneController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SubscribersController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TechnologiesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login',[AuthController::class,'login']);
Route::post('/register',[AuthController::class,'register']);
Route::middleware('auth:sanctum')->post('/logout',[AuthController::class,'logout']);

Route::get('/companies', [CompaniesController::class, 'index']);
Route::post('/companies', [CompaniesController::class, 'store']);
Route::get('/companies/{id}', [CompaniesController::class, 'show']);
Route::put('/companies/{id}', [CompaniesController::class, 'update']);
Route::delete('/companies/{id}', [CompaniesController::class, 'destroy']);

Route::get('/team', [TeamController::class, 'index']);
Route::post('/team', [TeamController::class, 'store']);
Route::get('/team/{id}', [TeamController::class, 'show']);
Route::put('/team/{id}', [TeamController::class, 'update']);
Route::delete('/team/{id}', [TeamController::class, 'destroy']);
// technologies routes
Route::get('/technologies', [TechnologiesController::class, 'index']);
Route::post('/technologies', [TechnologiesController::class, 'store']);
Route::get('/technologies/{id}', [TechnologiesController::class, 'show']);
Route::put('/technologies/{id}', [TechnologiesController::class, 'update']);
Route::delete('/technologies/{id}', [TechnologiesController::class, 'destroy']);
Route::get('/locations', [LocationsController::class, 'index']);
Route::post('/locations', [LocationsController::class, 'store']);
Route::get('/locations/{id}', [LocationsController::class, 'show']);
Route::put('/locations/{id}', [LocationsController::class, 'update']);
Route::delete('/locations/{id}', [LocationsController::class, 'destroy']);
Route::get('/milestones', [MilestoneController::class, 'index']);
Route::post('/milestones', [MilestoneController::class, 'store']);
Route::get('/milestones/{id}', [MilestoneController::class, 'show']);
Route::put('/milestones/{id}', [MilestoneController::class, 'update']);
Route::delete('/milestones/{id}', [MilestoneController::class, 'destroy']);
Route::get('/news', [NewsController::class, 'index']);
Route::post('/news', [NewsController::class, 'store']);
Route::get('/news/{id}', [NewsController::class, 'show']);
Route::put('/news/{id}', [NewsController::class, 'update']);
Route::delete('/news/{id}', [NewsController::class, 'destroy']);

Route::get('/settings', [SettingsController::class, 'index']);
Route::post('/settings', [SettingsController::class, 'update']);
Route::get('/about', [AboutController::class, 'index']);
Route::post('/about', [AboutController::class, 'update']);

Route::get('/abouthome', [AboutHomeController::class, 'index']);
Route::post('/abouthome', [AboutHomeController::class, 'update']);

// HeroSlider 
Route::get('/herobanner', [HeroBannerController::class, 'index']);
Route::post('/herobanner', [HeroBannerController::class, 'update']);
Route::post('/subscribe', [SubscribersController::class, 'subscribe']);