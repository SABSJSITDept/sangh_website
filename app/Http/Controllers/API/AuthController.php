<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }


public function updatePassword(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'current_password' => 'required',
        'new_password' => 'required|min:6|confirmed',
    ]);

    $user = User::find($request->user_id);

    if (!Hash::check($request->current_password, $user->password)) {
        return back()->with('error', '❌ वर्तमान पासवर्ड गलत है।');
    }

    $user->password = Hash::make($request->new_password);
    $user->save();

    return back()->with('success', '✅ पासवर्ड सफलतापूर्वक बदल दिया गया है।');
}
}

