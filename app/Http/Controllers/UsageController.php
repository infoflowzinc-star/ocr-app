<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OfficeUsage;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\StreamedResponse;

class UsageController extends Controller
{
    public function index(Request $request)
    {
        $query = OfficeUsage::with('office')->orderByDesc('yyyymm');

        if ($request->filled('yyyymm')) {
            $query->where('yyyymm', $request->input('yyyymm'));
        }

        if ($request->filled('office_name')) {
            $query->whereHas('office', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->input('office_name') . '%');
            });
        }

        $usages = $query->get();

        return Inertia::render('Dashboard/Usage', [
            'usages' => $usages,
            'filters' => $request->only('yyyymm', 'office_name'),
        ]);
    }

    public function update(Request $request, OfficeUsage $usage)
    {
        $request->validate([
            'billing_status' => 'required|string|in:未請求,請求済,支払済',
        ]);

        $usage->billing_status = $request->input('billing_status');
        $usage->save();

        return redirect()->back()->with('success', '請求状態を更新しました');
    }

    public function export()
    {
        $usages = OfficeUsage::with('office')->orderByDesc('yyyymm')->get();

        $headers = ['事務所名', '年月', '仕訳数', '請求状態'];

        $callback = function () use ($usages, $headers) {
            $stream = fopen('php://output', 'w');
            fputcsv($stream, $headers);

            foreach ($usages as $u) {
                fputcsv($stream, [
                    $u->office->name,
                    $u->yyyymm,
                    $u->entry_count,
                    $u->billing_status,
                ]);
            }

            fclose($stream);
        };

        return new StreamedResponse($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="usage.csv"',
        ]);
    }
}
