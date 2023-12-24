<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\pharmacyController;
use App\Http\Controllers\WarehouseController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


        
        
        
        
        
        Route::prefix('admin')->middleware(['auth:api' , 'isAdmin'])->group(function($router){
            Route::post('/admin_login',[AuthController::class,'warehouse_login']);
            //Route::get('/indexCat',[CatigoriesController::class,'indexCat']);
            //Route::get('/indexMed',[MedicineController::class,'indexMed']);
            //Route::get('/searchMed',[MedicineController::class,'searchMed']);
            //Route::get('/searchCat',[CatigoriesController::class,'searchCat']);
            
            
            
        });
        
        Route::prefix('auth')->middleware(['api'])->group(function($router){
            Route::post('/login', [AuthController::class, 'pharmacy_login']);
            Route::post('/register', [AuthController::class, 'register']);
            Route::post('/logout', [AuthController::class, 'logout']);
        });
        
        Route::middleware(['auth:api',])->group(function ($router) {
            //Route::get('index/catigories',[CatigorieApiController::class,'indexCat']);
            //Route::get('searchC',[CatigorieApiController::class,'searchC']);
            //Route::get('index/medicines',[MedicineApiController::class,'indexMed']);
            //Route::get('searchM',[MedicineApiController::class,'searchM']);
            //Route::get('index',[MedicineApiController::class,'index']);
            
        });
        
        Route::get('showCategories' , [MedicineController::class , 'showCategories']);
        Route::post('/insert' , [MedicineController::class , 'insert']); 
        Route::get('/details' , [MedicineController::class , 'details']); 