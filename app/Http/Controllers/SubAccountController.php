<?php

namespace App\Http\Controllers;

use App\Models\SubAccount;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubAccountController extends Controller
{
    public function index()
    {
        return SubAccount::with('account')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'code' => 'required|string|max:255',
            'name' => ['required', 'string', 'max:255', function ($attribute, $value, $fail) use ($request) {
                $account = Account::find($request->input('account_id'));
                if (!$account) return;

                $clientId = $account->client_id;

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
        ]);

        return SubAccount::create($validated);
    }

    public function show($id)
    {
        return SubAccount::with('account')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $sub = SubAccount::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255',
        ]);

        $sub->update($validated);
        return $sub;
    }

    public function destroy($id)
    {
        $sub = SubAccount::findOrFail($id);

        $used = DB::table('journal_entries')
            ->where('sub_account_id', $id)
            ->exists();

        if ($used) {
            return response()->json([
                'message' => 'この補助科目は仕訳で使用されているため削除できません'
            ], 400);
        }

        $sub->delete();
        return response()->noContent();
    }
}
