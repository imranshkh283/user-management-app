<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Http\Request;

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

Route::post('/auth/token', function (Request $request) {
    $user = User::where('email', $request->email)->first();

    if (!$user || !\Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    $token = $user->createToken('Test Token')->plainTextToken;

    return response()->json(['token' => $token]);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/create-user', [UserController::class, 'createUser'])->name('create-user');
    Route::get('/get-all-users', [UserController::class, 'getAllUsers'])->name('get-all-users');
});
