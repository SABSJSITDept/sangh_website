<?php

namespace App\Http\Controllers\Pravarti;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pravarti\Pravarti;

class PravartiShreeSanghController extends Controller
{
    public function index()
    {
        return Pravarti::all();
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required']);
        return Pravarti::create($request->only('name'));
    }

    public function update(Request $request, $id)
    {
        $pravarti = Pravarti::findOrFail($id);
        $pravarti->update($request->only('name'));
        return response()->json(['message' => 'Updated']);
    }

    public function destroy($id)
    {
        Pravarti::destroy($id);
        return response()->json(['message' => 'Deleted']);
    }
}
