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
            'user.email' => 'required|email:rfc',
            'user.password' => 'required|string|min:4|max:255|',
        ]);
        $jsonData = $req->json()->all();
        $name = $jsonData['user']['username'];
        $email = $jsonData['user']['email'];
        $password = $jsonData['user']['password'];

        $user = User::create([
            'username' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        return response()->json(["user" => $user]);
    }
}
