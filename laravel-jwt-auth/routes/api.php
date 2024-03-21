<?php
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MedicineController;

Route::post('/admin_login',[AuthController::class,'warehouse_login'])->middleware('isAdmin');

Route::prefix('admin')->middleware(['auth:api' , 'isAdmin'])->group(function($router){
    Route::post('/insert' , [MedicineController::class , 'insert']); 
    Route::get('/info' , [AuthController::class , 'showUserInformation']); 
    Route::post('/create', [CategoryController::class, 'create']);
    Route::post('/update/{id}', [CategoryController::class, 'update']);
    Route::delete('/delete/{id}', [CategoryController::class, 'delete']);
    Route::get('/showMedicines' , [MedicineController::class , 'showMedicines']); 
    Route::get('/getCategories' , [CategoryController::class , 'index']); 
    Route::get('/getCatMedicines' , [CategoryController::class , 'medicines']); 
    Route::get('/details' , [MedicineController::class , 'details']); 
    Route::get('/search' , [MedicineController::class , 'search']); 
    Route::get('/showOrder' , [MedicineController::class , 'viewAdminOrders']); 
    Route::post('/updateOrderStatus/{id}', [MedicineController::class , 'updateOrderStatus']);
    Route::post('/updateOrderPaymentStatus/{id}', [MedicineController::class , 'updateOrderPaymentStatus']);
});


Route::prefix('auth')->middleware(['api'])->group(function($router){
    Route::post('/login', [AuthController::class, 'pharmacy_login']);
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::post('/register', [AuthController::class, 'pharmacy_register']);  
            Route::get('/info' , [AuthController::class , 'showUserInformation']); 
            Route::get('/showMedicines' , [MedicineController::class , 'showMedicines']); 
            Route::get('/getCategories' , [CategoryController::class , 'index']); 
            Route::get('/getCatMedicines' , [CategoryController::class , 'medicines']); 
            Route::post('/order' , [MedicineController::class , 'order']); 
            Route::get('/details' , [MedicineController::class , 'details']); 
            Route::get('/search' , [MedicineController::class , 'search']); 
            Route::get('/showOrder' , [MedicineController::class , 'viewUserOrders']); 
        });
        