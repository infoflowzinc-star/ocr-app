<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Account;
use App\Models\SubAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    public function index()
    {
        return Client::with(['accounts', 'journalTemplates'])->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'office_id' => 'required|exists:offices,id',
            'name' => 'required|string',
            'industry' => 'nullable|string',
            'tax_code' => 'nullable|string',
        ]);

        // クライアント作成
        $client = Client::create($validated);

        // 同じ事務所内の既存クライアントから科目をコピー
        $source = Client::where('office_id', $client->office_id)
            ->where('id', '!=', $client->id)
            ->with('accounts.subAccounts')
            ->orderByDesc(DB::raw('LENGTH(name)')) // 任意の選定基準
            ->first();

        if ($source) {
            foreach ($source->accounts as $account) {
                $newAccount = $account->replicate();
                $newAccount->client_id = $client->id;
                $newAccount->save();

                foreach ($account->subAccounts as $sub) {
                    $newSub = $sub->replicate();
                    $newSub->account_id = $newAccount->id;
                    $newSub->save();
                }
            }
        }

        return response()->json($client);
    }

    public function show($id)
    {
        return Client::with(['accounts', 'clientUsers'])->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $client = Client::findOrFail($id);
        $client->update($request->all());
        return $client;
    }

    public function destroy($id)
    {
        Client::findOrFail($id)->delete();
        return response()->noContent();
    }
}
