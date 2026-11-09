<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request) {
        $validate = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name'     => $validate['name'],
            'email'    => $validate['email'],
            'password' => Hash::make($validate['password']),
        ]);

        return response()->json([
            'message' => 'Registrasi berhasil',
            'user' => $user,
        ], 200);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email atau password salah.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message'    => 'Login berhasil',
            'token'      => $token,
            'token_type' => 'Bearer',
            'user'       => $user,
        ], 200);
    }

    public function profile(Request $request) {
        return response()->json($request->user());
    }

    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();

         return response()->json([
            'message' => 'Logout berhasil',
        ], 200);
    }
}
