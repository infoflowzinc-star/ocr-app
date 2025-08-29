<?php

namespace App\Http\Controllers;

use App\Models\JournalTemplate;
use Illuminate\Http\Request;

class JournalTemplateController extends Controller
{
    public function index(Request $request)
    {
        $clientId = $request->query('client_id');

        $query = JournalTemplate::with(['debitAccount', 'creditAccount']);

        if ($clientId) {
            $query->where('client_id', $clientId);
        }

        return $query->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'title' => 'required|string|max:255',
            'debit_account_id' => 'nullable|exists:accounts,id',
            'credit_account_id' => 'nullable|exists:accounts,id|different:debit_account_id',
            'amount_type' => 'required|in:固定,変動',
            'description' => 'nullable|string|max:255',
        ]);

        return JournalTemplate::create($validated);
    }

    public function show($id)
    {
        return JournalTemplate::with(['debitAccount', 'creditAccount'])->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $template = JournalTemplate::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'debit_account_id' => 'nullable|exists:accounts,id',
            'credit_account_id' => 'nullable|exists:accounts,id|different:debit_account_id',
            'amount_type' => 'required|in:固定,変動',
            'description' => 'nullable|string|max:255',
        ]);

        $template->update($validated);
        return $template;
    }

    public function destroy($id)
    {
        $template = JournalTemplate::findOrFail($id);
        $template->delete();
        return response()->noContent();
    }
}
