<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Requests\AuthRequest;
use App\Models\User;

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

Route::prefix('users')->group(function () {
    Route::post('/create', [UserController::class, 'createUser'])->name('create');
    Route::post('/login', [UserController::class, 'login'])->name('login');
});

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/get-all-users', [UserController::class, 'getAllUsers'])->name('get-all-users');
    Route::put('/update-user/{id}', [UserController::class, 'updateUser'])->name('update-user')
        ->middleware(['checkEmailVerified']);
    Route::delete('/delete-user/{id}', [UserController::class, 'deleteUser'])->name('delete-user');
});
