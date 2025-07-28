<?php

// app/Http/Controllers/Shree_sangh/Karyakarini/PravartiSanyojakController.php
namespace App\Http\Controllers\Shree_sangh\Karyakarini;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShreeSangh\Karyakarini\PravartiSanyojak;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PravartiSanyojakController extends Controller
{
    public function index()
    {
        return PravartiSanyojak::with('pravarti')->latest()->get();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'       => 'required',
            'post'       => 'required',
            'city'       => 'required',
            'pravarti_id'=> 'required|exists:pravarti,id',
            'mobile'     => 'required',
            'photo'      => 'required|image|max:200' // ~200KB
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $path = $request->file('photo')->store('pravarti_sanyojak', 'public');

        PravartiSanyojak::create($request->only(['name', 'post', 'city', 'pravarti_id', 'mobile']) + ['photo' => $path]);

        return response()->json(['success' => true]);
    }

    public function update(Request $request, $id)
    {
        $data = PravartiSanyojak::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name'       => 'required',
            'post'       => 'required',
            'city'       => 'required',
            'pravarti_id'=> 'required|exists:pravarti,id',
            'mobile'     => 'required',
            'photo'      => 'nullable|image|max:200'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->hasFile('photo')) {
            Storage::disk('public')->delete($data->photo);
            $data->photo = $request->file('photo')->store('pravarti_sanyojak', 'public');
        }

        $data->update($request->only(['name', 'post', 'city', 'pravarti_id', 'mobile']));

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $data = PravartiSanyojak::findOrFail($id);
        Storage::disk('public')->delete($data->photo);
        $data->delete();

        return response()->json(['success' => true]);
    }
}

