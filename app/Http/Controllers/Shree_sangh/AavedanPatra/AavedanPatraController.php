<?php

namespace App\Http\Controllers\Shree_sangh\AavedanPatra;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShreeSangh\AavedanPatra\AavedanPatra;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AavedanPatraController extends Controller
{
    public function index()
    {
        return response()->json(AavedanPatra::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'file' => 'required',
        ]);

        if ($request->hasFile('file')) {
            $request->validate([
                'file' => 'mimes:pdf|max:2048',
            ]);

            $filename = time().'_'.Str::random(10).'.'.$request->file('file')->extension();
            $request->file('file')->storeAs('public/aavedan_patra', $filename);
        } else {
            $filename = $request->file; // google form link
        }

        $data = AavedanPatra::create([
            'name' => $request->name,
            'file' => $filename,
        ]);

        return response()->json($data, 201);
    }

    public function update(Request $request, $id)
    {
        $data = AavedanPatra::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        if ($request->hasFile('file')) {
            $request->validate([
                'file' => 'mimes:pdf|max:2048',
            ]);

            // delete old
            if (Storage::exists('public/aavedan_patra/' . $data->file)) {
                Storage::delete('public/aavedan_patra/' . $data->file);
            }

            $filename = time().'_'.Str::random(10).'.'.$request->file('file')->extension();
            $request->file('file')->storeAs('public/aavedan_patra', $filename);
        } else {
            $filename = $request->file;
        }

        $data->update([
            'name' => $request->name,
            'file' => $filename,
        ]);

        return response()->json($data);
    }

    public function destroy($id)
    {
        $data = AavedanPatra::findOrFail($id);
        if (Storage::exists('public/aavedan_patra/' . $data->file)) {
            Storage::delete('public/aavedan_patra/' . $data->file);
        }
        $data->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
