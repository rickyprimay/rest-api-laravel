<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    // Read
    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response(['message' => 'User not found'], 404);
        }

        return response(['user' => $user], 200);
    }

    // Update
    public function update(Request $request, $id)
    {
        $userData = $request->all();

        // Validation rules
        $rules = [
            'name' => 'max:60',
            'email' => 'email:rfc,dns|unique:users,email,' . $id,
            'password' => 'min:8',
            'no_telp' => 'regex:/^08[0-9]{9,12}$/',
            'image' => 'image|mimes:jpeg,png,jpg|max:2048',
        ];

        // Run validation
        $validator = Validator::make($userData, $rules);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()], 400);
        }

        // Check if user exists
        $user = User::find($id);

        if (!$user) {
            return response(['message' => 'User not found'], 404);
        }

        // Update user data
        $user->update($userData);

        return response(['message' => 'User updated successfully', 'user' => $user], 200);
    }

    // Delete
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response(['message' => 'User not found'], 404);
        }

        $user->delete();

        return response(['message' => 'User deleted successfully'], 200);
    }

    // ...
}
