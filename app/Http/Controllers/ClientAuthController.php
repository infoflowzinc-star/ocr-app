<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ClientUser;

class ClientAuthController extends Controller
{
    /**
     * クライアントユーザーのログイン
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('client_user')->attempt($credentials)) {
            $user = Auth::guard('client_user')->user();
            return response()->json([
                'message' => 'ログイン成功',
                'user' => $user,
            ]);
        }

        return response()->json([
            'message' => 'ログイン失敗',
        ], 401);
    }

    /**
     * クライアントユーザーのログアウト
     */
    public function logout(Request $request)
    {
        Auth::guard('client_user')->logout();
        return response()->json([
            'message' => 'ログアウトしました',
        ]);
    }

    /**
     * ログイン中のクライアントユーザー情報取得
     */
    public function me(Request $request)
    {
        $user = Auth::guard('client_user')->user();
        return response()->json($user);
    }
}
