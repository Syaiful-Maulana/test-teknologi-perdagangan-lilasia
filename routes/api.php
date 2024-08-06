<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;

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

// Public routes
Route::post('register/admin', [AuthController::class, 'register_admin']);
Route::post('register/user', [AuthController::class, 'register_user']);
Route::post('login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/{id}', [ProductController::class, 'show']);
        Route::post('/', [ProductController::class, 'store'])->middleware('can:create,App\Models\Product');
        Route::put('/{product}', [ProductController::class, 'update'])->middleware('can:update,App\Models\Product');
        Route::delete('/{product}', [ProductController::class, 'destroy'])->middleware('can:delete,App\Models\Product');
    });
    Route::post('logout', [AuthController::class, 'logout']);
});
