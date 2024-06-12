<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function usersOwner(Request $request)
    {
        return response()->json([
            'pendingApprove' => User::where('role', 'client')->where('ownerStatus', 'pendingApproved')->get(),
            'approved' => User::where('role', 'owner')->where('ownerStatus', 'approved')->get(),
        ], 200);
    }

    public function approveOwner($id)
    {
        $user = User::where('id', $id);

        if ($user->first()->ownerStatus == null) {
            return response()->json([
                'status' => 'error',
                'message' => 'Owner Not Register!'
            ], 405);
        }

        if ($user->first()->ownerStatus == 'approved') {
            return response()->json([
                'status' => 'error',
                'message' => 'You already Approve this Account!',
            ], 405);
        }

        $user->update([
            'role' => 'owner',
            'ownerStatus' => 'approved',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Owner Approved Success!',
        ], 200);
    }

    public function declineOwner($id)
    {
        $user = User::where('id', $id);

        if ($user->first()->ownerStatus == null) {
            return response()->json([
                'status' => 'error',
                'message' => 'Owner Not Register!'
            ], 405);
        }

        if ($user->first()->ownerStatus == 'notApproved') {
            return response()->json([
                'status' => 'error',
                'message' => 'You already Decline this Account!',
            ], 405);
        }

        $user->update([
            'ownerStatus' => 'notApproved',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Owner Decline Success!',
        ], 200);
    }

    public function deleteOwner($id)
    {
        $user = User::where('id', $id);

        if ($user->first()->ownerStatus == null) {
            return response()->json([
                'status' => 'error',
                'message' => 'Owner Not Register!'
            ], 405);
        }

        if (!$user->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Owner Not Exists!',
            ], 400);
        }

        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Owner Delete Success!',
        ]);
    }
}
