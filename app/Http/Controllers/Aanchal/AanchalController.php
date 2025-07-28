<?php

namespace App\Http\Controllers\Aanchal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Aanchal\Aanchal;

class AanchalController extends Controller
{
    public function index()
    {
        return response()->json(Aanchal::all());
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required']);
        $aanchal = Aanchal::create($request->only('name'));
        return response()->json($aanchal, 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required']);
        $aanchal = Aanchal::findOrFail($id);
        $aanchal->update($request->only('name'));
        return response()->json($aanchal);
    }

    public function destroy($id)
    {
        Aanchal::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted']);
    }

    public function show($id)
    {
        return response()->json(Aanchal::findOrFail($id));
    }
}
