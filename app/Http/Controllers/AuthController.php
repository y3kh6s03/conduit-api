<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['register', 'login', 'respondWithToken']]);
    }

    public function register(Request $req)
    {
        $req->validate([
            'user.username' => 'required|string|min:3|max:255|',
            'user.email' => 'required',
            'user.password' => 'required|string|min:4|max:255|',
        ]);
        $jsonData = $req->json()->all();
        $name = $jsonData['user']['username'];
        $email = $jsonData['user']['email'];
        $password = $jsonData['user']['password'];

        $user = User::create([
            'email' => $email,
            'username' => $name,
            'password' => Hash::make($password),
        ]);

        $credentials = ['email' => $email, 'password' => $password];
        $token = auth()->attempt($credentials);
        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return response()->json([
            "user" => [
                "email" => $email,
                "token" => $token,
                "username" => $name,
            ]
        ]);
    }

    public function login(Request $req)
    {
        $req->validate([
            'user.email' => ['required'],
            'user.password' => ['required', 'string', 'min:4', 'max:255']
        ]);
        $jsonData = $req->json()->all();
        $email = $jsonData['user']['email'];
        $password = $jsonData['user']['password'];
        $credentials = ['email' => $email, 'password' => $password];
        $token = auth()->attempt($credentials);
        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $user = auth()->user();
        $jwtToken = $this->respondWithToken($token)->original['access_token'];

        return response()->json([
            "user" => [
                "email" => $email,
                "token" => $jwtToken,
                "username" => $user->name
            ]
        ]);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
