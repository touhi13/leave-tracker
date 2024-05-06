<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;


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

Route::prefix('v1')->group(function () {

    Route::get('/process-queue', function () {
        Artisan::call('queue:listen');
    
        return 'Queue Processed Successfully';
    });

    // dd("hh");
    $adminRole = User::ADMIN_ROLES;
    $allRole   = User::ALL_ROLES;

    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'logout']);
    // Route::post('leave-request', [LeaveRequestController::class, 'store']);

    Route::group(['middleware' => ['token.verify', 'role.verify:' . implode(',', $allRole)]], function () {
        Route::post('leave-request', [LeaveRequestController::class, 'store']);
        Route::get('leave-request', [LeaveRequestController::class, 'all']);

    });

    Route::group(['middleware' => ['token.verify', 'role.verify:' . implode(',', $adminRole)]], function () {
        Route::post('update-user-status', [UserController::class, 'updateUserStatus']);
        Route::put('leave-request/{id}', [LeaveRequestController::class, 'manage']);
        Route::get('leave-request-count', [LeaveRequestController::class, 'leaveRequestsCounts']);
        Route::get('user', [UserController::class, 'all']);
    });
});
