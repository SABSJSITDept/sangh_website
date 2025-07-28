<?php

namespace App\Http\Controllers\Shree_sangh\Karyakarini;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShreeSangh\Karyakarini\Pst;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PstController extends Controller
{
    public function index()
    {
        return Pst::all();
    }

public function store(Request $request)
{
    // 🔒 Limit check: Max 4 entries allowed
    if (Pst::count() >= 4) {
        return response()->json(['error' => 'केवल 4 प्रविष्टियाँ ही अनुमत हैं।'], 403);
    }

    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'post' => 'required|string|max:255',
        'photo' => 'nullable|image|max:200', // max 2MB
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    $data = $request->only(['name', 'post']);

    if ($request->hasFile('photo')) {
        $data['photo'] = $request->file('photo')->store('pst', 'public');
    }

    $pst = Pst::create($data);

    return response()->json($pst);
}

    public function update(Request $request, $id)
    {
        $pst = Pst::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'post' => 'required|string|max:255',
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $pst->name = $request->name;
        $pst->post = $request->post;

        if ($request->hasFile('photo')) {
            if ($pst->photo) {
                Storage::disk('public')->delete($pst->photo);
            }
            $pst->photo = $request->file('photo')->store('pst', 'public');
        }

        $pst->save();

        return response()->json($pst);
    }

    public function destroy($id)
    {
        $pst = Pst::findOrFail($id);
        if ($pst->photo) {
            Storage::disk('public')->delete($pst->photo);
        }
        $pst->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }

    public function show($id)
    {
        return Pst::findOrFail($id);
    }
}
