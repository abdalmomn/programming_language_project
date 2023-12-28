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
    Route::post('/insert' , [MedicineController::class , 'insert']); 
    Route::get('/info' , [AuthController::class , 'showUserInformation']); 
        });
        
        
        Route::prefix('auth')->middleware(['api'])->group(function($router){
            Route::post('/login', [AuthController::class, 'pharmacy_login']);
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::post('/register', [AuthController::class, 'pharmacy_register']);
            Route::get('/info' , [AuthController::class , 'showUserInformation']); 
        });
        
        Route::get('/details' , [MedicineController::class , 'details']); 
        Route::get('/search' , [MedicineController::class , 'search']); 
        Route::get('/showMedicines' , [MedicineController::class , 'showMedicines']); 
        Route::post('/showCategory' , [MedicineController::class , 'showCategory']); 
        Route::post('/order' , [MedicineController::class , 'order']); 
        Route::get('/showOrder/{id}' , [MedicineController::class , 'showOrderInCart']); 
        Route::put('/updateOrderStatus/{id}/status/{status}', [MedicineController::class , 'updateOrderStatusAdmin'])->where(['status' => 'pending|processing|completed|cancelled']);
        Route::put('/updateOrderPayment/{id}/status/{status}', [MedicineController::class , 'updatePaymentStatusAdmin'])->where(['status' => 'paid|unpaid']);