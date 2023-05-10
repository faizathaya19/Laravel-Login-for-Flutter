<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use exception;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    function Register(Request $R)
    {
        try {
            $cred = new user();
            $cred->name = $R->name;
            $cred->email = $R->email;
            $cred->password = Hash::make($R->password);
            $cred->save();
            $response = ['status' => 200, 'message' => 'Register Succesfully!'];
            return response()->json($response);
        } catch (exception $e) {
            $response = ['status' => 500, 'message' => $e];
        }
    }

    function Login(Request $R)
    {
        $user = User::where('email', $R->email)->first();

        if ($user != '[]' && Hash::check($R->password, $user->password)) {
            $token = $user->createToken('Personal Access Token')->plainTextToken;
            $response = ['status' => 200, 'token' => $token, 'user' => $user, 'message' => 'Succesfully Login! Welcome Back'];
            return response()->json($response);
        } else if ($user == '[]') {
            $response = ['status' => 500, 'message' => 'No account found with this email'];
            return response()->json($response);
        } else {
            $response = ['status' => 500, 'message' => 'Wrong email or password! please try again'];
            return response()->json($response);
        }
    }
}
