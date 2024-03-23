<?php
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MedicineController;

        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/register', [AuthController::class, 'register']);
        
    Route::prefix('admin')->middleware(['auth:api' , 'isAdmin'])->group(function($router){
        Route::post('/insert' , [MedicineController::class , 'insert']); 
        Route::post('/create', [CategoryController::class, 'create']);
        Route::post('/update/{id}', [CategoryController::class, 'update']);
        Route::post('/updateOrderStatus/{id}', [MedicineController::class , 'updateOrderStatus']);
        Route::get('/info' , [AuthController::class , 'showUserInformation']); 
        Route::get('/showMedicines' , [MedicineController::class , 'showAdminMedicines']); 
        Route::get('/getCategories' , [CategoryController::class , 'index']); 
        Route::get('/getCatMedicines' , [CategoryController::class , 'medicines']); 
        Route::get('/details' , [MedicineController::class , 'details']); 
        Route::get('/search' , [MedicineController::class , 'search']); 
        Route::get('/showOrder' , [MedicineController::class , 'viewAdminOrders']); 
    });

    Route::prefix('auth')->middleware(['api'])->group(function($router){
            Route::post('/logout', [AuthController::class, 'logout']);
            Route::get('/info' , [AuthController::class , 'showUserInformation']); 
            Route::get('/showMedicines/{adminId}' , [MedicineController::class , 'showUserMedicines']); 
            Route::get('/getCategories' , [CategoryController::class , 'index']); 
            Route::get('/getCatMedicines' , [CategoryController::class , 'medicines']); 
            Route::post('/order' , [MedicineController::class , 'order']); 
            Route::get('/details' , [MedicineController::class , 'details']); 
            Route::get('/search' , [MedicineController::class , 'search']); 
            Route::get('/showOrder/{userId}/{orderId}' , [MedicineController::class , 'viewUserOrders']); 
    });