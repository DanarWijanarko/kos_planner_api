<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = Validator::make($request->input(), [
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'gender' => 'required',
            'role' => 'in:admin,owner,client',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'errors' => $validated->errors()->all(),
                'status' => 'error',
                'message' => 'An error has occurred',
            ], 422);
        }

        $user = User::create($validated->getData());

        return response()->json([
            'data' => $user->toJson(),
            'status' => 'success',
            'message' => 'Successfully Register!',
        ], 201);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'The Provided Credential Not Match to our Database!',
            ]);
        }

        $token = $user->createToken($user->name, ['*'], now()->addHour())->plainTextToken;

        return response()->json([
            'status' => 'success',
            'token' => $token,
            'Type' => 'Bearer',
            'data' => $user,
            'expired' => config('sanctum.expiration') . ' Minutes',
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Successfuly Logout!'
        ], 200);
    }
}
