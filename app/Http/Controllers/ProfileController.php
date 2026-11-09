<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show(Request $request) {
        return response()->json([
            'data' => $request->user(),
        ]);
    }

    public function update(Request $request) {
        $user = $request->user();

        $validate = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update($validate);

        return response()->json([
            'message' => 'Profile updated successfully!',
            'data' => $request->user(),
        ]);
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password'         => ['required', 'confirmed', Password::min(8)],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json([
            'message' => 'Password updated successfully!',
        ]);
    }
}
