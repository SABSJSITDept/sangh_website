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
            'file_type' => 'required|in:pdf,google_form',
            'file' => $request->file_type === 'pdf'
                ? 'required|mimes:pdf|max:2048'
                : 'required|string',
        ]);

        if ($request->file_type === 'pdf' && $request->hasFile('file')) {
            $filename = time().'_'.Str::random(10).'.'.$request->file('file')->extension();
            $request->file('file')->storeAs('public/aavedan_patra', $filename);
            $filePath = $filename;
        } else {
            $filePath = $request->file; // google form link
        }

        $data = AavedanPatra::create([
            'name' => $request->name,
            'file' => $filePath,
            'file_type' => $request->file_type,
        ]);

        return response()->json($data, 201);
    }

    public function update(Request $request, $id)
    {
        $data = AavedanPatra::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'file_type' => 'required|in:pdf,google_form',
            'file' => $request->file_type === 'pdf'
                ? 'nullable|mimes:pdf|max:2048'
                : 'required|string',
        ]);

        if ($request->file_type === 'pdf' && $request->hasFile('file')) {
            if ($data->file_type === 'pdf' && Storage::exists('public/aavedan_patra/' . $data->file)) {
                Storage::delete('public/aavedan_patra/' . $data->file);
            }

            $filename = time().'_'.Str::random(10).'.'.$request->file('file')->extension();
            $request->file('file')->storeAs('public/aavedan_patra', $filename);
            $filePath = $filename;
        } elseif ($request->file_type === 'google_form') {
            $filePath = $request->file;
        } else {
            $filePath = $data->file;
        }

        $data->update([
            'name' => $request->name,
            'file' => $filePath,
            'file_type' => $request->file_type,
        ]);

        return response()->json($data);
    }

    public function destroy($id)
    {
        $data = AavedanPatra::findOrFail($id);

        if ($data->file_type === 'pdf' && Storage::exists('public/aavedan_patra/' . $data->file)) {
            Storage::delete('public/aavedan_patra/' . $data->file);
        }

        $data->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
