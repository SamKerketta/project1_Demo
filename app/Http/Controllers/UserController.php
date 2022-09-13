<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
//use Laravel\Sanctum\HasApiTokens;

use Illuminate\Http\Request;


class UserController extends Controller
{
    public function register(Request $request)
    {
        //Validated
        $validateUser = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|confirmed'
            ]
        );
        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateUser->errors()
            ], 401);
        }

        if (User::where('email', $request->email)->first()) {
            return response([
                'message' => 'Email already exist',
                'status' => 'failed'
            ], 200);
        }


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        return response()->json([
            'status' => true,
            'message' => 'User Created Successfully',
            'token' => $user->createToken("API TOKEN")->plainTextToken
        ], 200);
    }

    // login of the user
    public function login(Request $request)
    {
        $validateUser = Validator::make(
            $request->all(),
            [
                'email' => 'required|email',
                'password' => 'required'
            ]
        );

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateUser->errors()
            ], 401);
        }

        if ($user = User::where('email', $request->email)->first()) {
            if (Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => true,
                    'message' => 'User Logged In Successfully',
                    'token' => $user->createToken("API TOKEN")->plainTextToken
                ], 200);
            }
            return response()->json([
                'status' => false,
                'message' => 'Password does not match with our record.',
            ], 401);
        }
        return response()->json([
            'status' => false,
            'message' => 'Email  does not match with our record.',
        ], 401);
    }

    //Logout
    public function logout()
    {

        $user = Auth::user();
        // this error is working 
        $user->tokens()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Logout Success.',
        ], 200);
    }

    //Logged In User
    public function logged_user()
    {
        $logged_user = auth()->user();
        return response()->json([
            'user' => $logged_user,
            'status' => true,
            'message' => 'Logged User Data'
        ], 200);
    }

    //change the password
    public function changePassword(Request $request)
    {
        $validateUser = Validator::make(
            $request->all(),
            [
                'password' => 'required'
            ]
        );
        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateUser->errors()
            ], 401);
        }
        $userpass = Auth::user();
        $userpass->password = Hash::make($request->password);
        $userpass->save();
        return response()->json([
            'message' => 'new password saved',
            'state' => true
        ], 201);
    }
}
