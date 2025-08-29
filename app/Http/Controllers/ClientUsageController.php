<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientUsageController extends Controller
{
    /**
     * クライアントの利用状況一覧
     */
    public function index()
    {
        return Client::withCount([
            'clientUsers',
            'accounts',
            'journalTemplates',
        ])->get();
    }

    /**
     * 特定クライアントの利用状況詳細
     */
    public function show($id)
    {
        $client = Client::withCount([
            'clientUsers',
            'accounts',
            'journalTemplates',
        ])->findOrFail($id);

        return response()->json([
            'client_id' => $client->id,
            'name' => $client->name,
            'users_count' => $client->client_users_count,
            'accounts_count' => $client->accounts_count,
            'templates_count' => $client->journal_templates_count,
        ]);
    }
}
