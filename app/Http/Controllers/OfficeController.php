<?php

namespace App\Http\Controllers;

use App\Models\Office;
use Illuminate\Http\Request;

class OfficeController extends Controller
{
    public function index()
    {
        return Office::withCount(['clients', 'users'])->get();
    }

    public function store(Request $request)
    {
        return Office::create($request->validate([
            'name' => 'required|string',
            'code' => 'required|string|unique:offices,code',
        ]));
    }

    public function show($id)
    {
        return Office::with(['clients', 'users'])->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $office = Office::findOrFail($id);
        $office->update($request->all());
        return $office;
    }

    public function destroy($id)
    {
        Office::findOrFail($id)->delete();
        return response()->noContent();
    }
}
