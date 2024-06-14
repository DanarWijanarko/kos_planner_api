<?php

namespace App\Http\Controllers;

use App\Models\Dorm;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class OwnerController extends Controller
{
    public function getDorms($user_id)
    {
        return User::find($user_id)->dorms;
    }

    public function getDormDetail($dorm_id)
    {
        return Dorm::find($dorm_id);
    }
    public function getRoomDetail($room_id)
    {
        return Room::find($room_id);
    }

    public function storeDorm(Request $request, $user_id)
    {
        $validated = Validator::make($request->input(), [
            'name' => 'required|string|max:255',
            'images' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'address' => 'required|string|max:255',
            'longtitude' => 'required|string|max:255',
            'latitude' => 'required|string|max:255',
            'capacity' => 'required|integer',
            'type' => 'required|string|max:50',
            'description' => 'string',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'errors' => $validated->errors()->all(),
                'status' => 'error',
                'message' => 'An error has occurred',
            ], 422);
        }

        if ($request->hasFile('images')) {
            $imageName = time() . '.' . $request->images->extension();
            $request->images->storeAs('images', $imageName, 'public');
            // http://server.test/storage/images/
        } else {
            $imageName = null;
        }

        Dorm::create([
            'user_id' => $user_id,
            'name' => $validated->getData()['name'],
            'images' => $imageName,
            'address' => $validated->getData()['address'],
            'longtitude' => $validated->getData()['longtitude'],
            'latitude' => $validated->getData()['latitude'],
            'capacity' => $validated->getData()['capacity'],
            'type' => $validated->getData()['type'],
            'description' => $validated->getData()['description'],
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Dorm has been Added.'
        ]);
    }

    public function editDorm(Request $request, $user_id, $dorm_id)
    {
        $validated = Validator::make($request->input(), [
            'name' => 'required|string|max:255',
            'images' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'address' => 'required|string|max:255',
            'longtitude' => 'required|string|max:255',
            'latitude' => 'required|string|max:255',
            'capacity' => 'required|integer',
            'type' => 'required|string|max:50',
            'description' => 'string',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'errors' => $validated->errors()->all(),
                'status' => 'error',
                'message' => 'An error has occurred',
            ], 422);
        }

        $dorm = Dorm::find($dorm_id);

        if ($request->hasFile('images')) {
            if ($dorm->images) {
                Storage::delete('/public/images/' . $dorm->images);
            }

            $imageName = time() . '.' . $request->images->extension();
            $request->images->storeAs('images', $imageName, 'public');
            // http://server.test/storage/images/
        } else {
            $imageName = $dorm->images;
        }

        $dorm->user_id = $user_id;
        $dorm->name = $request->name;
        $dorm->images = $imageName;
        $dorm->address = $request->address;
        $dorm->longtitude = $request->longtitude;
        $dorm->latitude = $request->latitude;
        $dorm->capacity = $request->capacity;
        $dorm->type = $request->type;
        $dorm->description = $request->description;

        $dorm->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Dorm "' . $dorm->name . '" has been Updated.'
        ]);
    }

    public function deleteDorm($dorm_id)
    {
        $dorm = Dorm::where('id', $dorm_id);

        if (!$dorm->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dorm Not Exists!',
            ], 400);
        }

        $dorm->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Dorm Delete Success!',
        ]);
    }

    public function getRoom($dorm_id)
    {
        return Dorm::find($dorm_id)->rooms;
    }

    public function storeRoom(Request $request, $dorm_id)
    {
        $validated = Validator::make($request->input(), [
            'room_number' => 'required|string|max:255',
            'room_type' => 'required|string|max:255',
            'facilities' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'images' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'price' => 'required|integer',
            'available' => 'required|string|max:15',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'errors' => $validated->errors()->all(),
                'status' => 'error',
                'message' => 'An error has occurred',
            ], 422);
        }

        if ($request->hasFile('images')) {
            $imageName = time() . '.' . $request->images->extension();
            $request->images->storeAs('images', $imageName, 'public');
            // http://server.test/storage/images/
        } else {
            $imageName = null;
        }

        Room::create([
            'dorm_id' => $dorm_id,
            'room_number' => $validated->getData()['room_number'],
            'room_type' => $validated->getData()['room_type'],
            'facilities' => $validated->getData()['facilities'],
            'description' => $validated->getData()['description'],
            'images' => $imageName,
            'price' => $validated->getData()['price'],
            'available' => $validated->getData()['available'],
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Room has been Added.'
        ]);
    }

    public function editRoom(Request $request, $dorm_id, $room_id)
    {
        $validated = Validator::make($request->input(), [
            'room_number' => 'required|string|max:255',
            'room_type' => 'required|string|max:255',
            'facilities' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'images' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'price' => 'required|integer',
            'available' => 'required|string',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'errors' => $validated->errors()->all(),
                'status' => 'error',
                'message' => 'An error has occurred',
            ], 422);
        }

        $room = Room::find($room_id);

        if ($request->hasFile('images')) {
            if ($room->images) {
                Storage::delete('/public/images/' . $room->images);
            }

            $imageName = time() . '.' . $request->images->extension();
            $request->images->storeAs('images', $imageName, 'public');
            // http://server.test/storage/images/
        } else {
            $imageName = null;
        }

        $room->dorm_id = $dorm_id;
        $room->room_number = $request->room_number;
        $room->room_type = $request->room_type;
        $room->facilities = $request->facilities;
        $room->description = $request->description;
        $room->images = $imageName;
        $room->price = $request->price;
        $room->available = $request->available;

        $room->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Room "' . $room->room_type . '" has been Updated.'
        ]);
    }
}
