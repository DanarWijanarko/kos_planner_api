<?php

use App\Models\Dorm;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;

Route::prefix('v1')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);

    Route::middleware(['auth:sanctum'])->group(function () {
        // ! Logout Account => Delete Auth Token
        Route::post('/logout', [AuthController::class, 'logout']);

        // ! Admin
        Route::prefix('admin')->middleware(['restrictRole:admin'])->group(function () {
            // ? Get All Users for Admin that show only Owner
            Route::get('/owners', [AdminController::class, 'usersOwner']);
            // ? Approve new Owner
            Route::patch('/owner-approve/{id}', [AdminController::class, 'approveOwner']);
            // ? Decline new Owner
            Route::patch('/owner-decline/{id}', [AdminController::class, 'declineOwner']);
            // ? Delete Owner
            Route::delete('/owner/{id}', [AdminController::class, 'deleteOwner']);
        });

        // ! Owner
        Route::prefix('owner')->middleware(['restrictRole:owner'])->group(function () {
            Route::get('/dorms', function () {
                return Dorm::all();
            });
        });

        // ! Client
        Route::prefix('client')->middleware(['restrictRole:client'])->group(function () {
            Route::patch('/register-owner', function (Request $request) {
                if ($request->user()->ownerStatus) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'You already register as Owner, Please wait until admin Approved it.',
                    ], 405);
                }

                User::where('id', $request->user()->id)->update([
                    'ownerStatus' => 'pendingApproved'
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Please wait admin to approve it.',
                ], 200);
            });
        });
    });
});
