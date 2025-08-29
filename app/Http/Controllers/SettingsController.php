<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function get(Request $request)
    {
        // 仮の設定データ（DB接続前提）
        return response()->json([
            'default_tax_rate' => 10,
            'currency' => 'JPY',
            'auto_backup' => true,
        ]);
    }

    public function update(Request $request)
    {
        // 設定更新処理（バリデーション＋保存）
        return response()->json(['message' => '設定を更新しました']);
    }
}
