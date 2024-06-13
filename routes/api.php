<?php

use App\Http\Controllers\OwnerController;
use App\Models\Dorm;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Validator;

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
            // ! Dorms
            Route::prefix('dorms')->group(function () {
                // ? Get List of Dorms Based on User Owner
                Route::get('/{user_id}', [OwnerController::class, 'getDorms']);

                Route::get('/detail/{dorm_id}', [OwnerController::class, 'getDormDetail']);

                // ? Create Dorm Based on User Owner
                Route::post('/{user_id}', [OwnerController::class, 'storeDorm']);

                // ? Update Dorm Based on User Owner and dorm ID
                // ! Ubah Method menjadi PUT!!!
                Route::put('/{user_id}/edit/{dorm_id}', [OwnerController::class, 'editDorm']);

                Route::delete('/{dorm_id}', [OwnerController::class, 'deleteDorm']);
            });

            Route::prefix('rooms')->group(function () {
                // ? Get List of Dorms Based on Dorm
                Route::get('/{dorm_id}', [OwnerController::class, 'getRoom']);

                // ? Create Dorm Based on Dorm
                Route::post('/{dorm_id}', [OwnerController::class, 'storeRoom']);

                // ? Create Dorm Based on Dorm and room
                // ! Ubah Method menjadi PUT!!!
                Route::post('/{dorm_id}/edit/{room_id}', [OwnerController::class, 'editRoom']);
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
