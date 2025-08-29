<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{
    public function index()
    {
        return Account::with('subAccounts')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'code' => 'required|string|max:255',
            'name' => ['required', 'string', 'max:255', function ($attribute, $value, $fail) use ($request) {
                $clientId = $request->input('client_id');

                $existsInAccounts = DB::table('accounts')
                    ->where('client_id', $clientId)
                    ->where('name', $value)
                    ->exists();

                $existsInSubAccounts = DB::table('sub_accounts')
                    ->join('accounts', 'sub_accounts.account_id', '=', 'accounts.id')
                    ->where('accounts.client_id', $clientId)
                    ->where('sub_accounts.name', $value)
                    ->exists();

                if ($existsInAccounts || $existsInSubAccounts) {
                    $fail('この科目名は既に使用されています（勘定科目または補助科目）');
                }
            }],
            'type' => 'required|string',
        ]);

        return Account::create($validated);
    }

    public function show($id)
    {
        return Account::with('subAccounts')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $account = Account::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        $account->update($validated);
        return $account;
    }

    public function destroy($id)
    {
        Account::findOrFail($id)->delete();
        return response()->noContent();
    }
}
