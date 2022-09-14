<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\password_reset;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Mail\Message;

use Carbon\Carbon;

class ResetPasswordController extends Controller
{
    public function send(Request $request)
    {
        $validateUser = Validator::make(
            $request->all(),
            [
                'email' => 'required|email',
            ]
        );
        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateUser->errors()
            ], 401);
        }

        //email define
        $email = $request->email;

        $user = User::where('email', $email)->first();
        if (!$user) {
            return response([
                'message' => 'Email not exist',
                'status' => 'failed'
            ], 401);
        }
        // generate the token
        $token = Str::random(20);
        

        //save the data in the table in the database
        password_reset::create([
            'email' => $email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);
        
        // blade operation
        Mail::send('reset', ['token' => $token], function (message $message) use ($email) {
            $message->subject('reset your password');
            $message->to($email);
        });

        return response()->json([
            'status' => true,
            'message' => 'reset password email send ... check your email',
        ],200);
    }
}
