<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'ログイン成功',
                'user' => Auth::user(),
            ]);
        }

        return response()->json(['message' => 'ログイン失敗'], 401);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json(['message' => 'ログアウトしました']);
    }

    public function me()
    {
        return response()->json(Auth::user());
    }
}
