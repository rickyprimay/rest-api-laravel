<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    // Register
    public function register(Request $request) {
        $registrationData = $request->all();

        $validate = Validator::make($registrationData, [
            'name' => 'required|max:60',
            'email' => 'required|email:rfc,dns|unique:users',
            'password' => 'required|min:8',
            'no_telp' => 'regex:/^08[0-9]{9,12}$/',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);        
        if ($validate->fails()) {
            return response(['message' => $validate->errors()],400);
        }
        $registrationData['status'] = 0;
        $registrationData['password'] = bcrypt($request->password);

        $user = User::create($registrationData);

        return response([
            'message'=> 'Register Success', 
            'user' => $user
        ],200);
    }

    // Login
    public function login(Request $request) {
        $loginData = $request->all();

        $validate = Validator::make($loginData, [
            'email' => 'required|email:rfc,dns',
            'password' => 'required',
        ]);

        if ($validate->fails()) {
            return response(['message'=> $validate->errors()],400);
        }

        if (!Auth::attempt($loginData)) {
            return response(['message'=> 'Invalid Credentials'],401);
        }

        /**
         * @var \App\Models\User $user
         */

        $user = Auth::user();
        $token = $user->createToken('Authentication Token')->accessToken;

        return response([
            'message' => 'Authenticated',
            'user' => $user,
            'token_type' => 'Bearer',
            'access_token' => $token,
        ]);
    }

    // Logout
}