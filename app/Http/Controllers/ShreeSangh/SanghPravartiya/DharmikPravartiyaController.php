<?php

namespace App\Http\Controllers\ShreeSangh\SanghPravartiya;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShreeSangh\SanghPravartiya\DharmikPravartiya;

class DharmikPravartiyaController extends Controller
{
    public function index()
    {
        return response()->json(DharmikPravartiya::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'heading' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $pravartiya = DharmikPravartiya::create($validated);
        return response()->json($pravartiya);
    }

    public function update(Request $request, $id)
    {
        $pravartiya = DharmikPravartiya::findOrFail($id);
        $pravartiya->update($request->only('heading', 'content'));

        return response()->json($pravartiya);
    }

    public function destroy($id)
    {
        $pravartiya = DharmikPravartiya::findOrFail($id);
        $pravartiya->delete();

        return response()->json(['success' => true]);
    }
}
