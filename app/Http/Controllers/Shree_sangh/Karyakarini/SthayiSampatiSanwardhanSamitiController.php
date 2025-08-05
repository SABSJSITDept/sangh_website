<?php

namespace App\Http\Controllers\Shree_sangh\Karyakarini;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShreeSangh\Karyakarini\SthayiSampatiSanwardhanSamiti;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SthayiSampatiSanwardhanSamitiController extends Controller
{
    public function index()
    {
        return response()->json(SthayiSampatiSanwardhanSamiti::all());
    }

public function store(Request $request)
{
    $request->validate([
        'name'   => ['required', 'regex:/^[\p{L}\s]+$/u'],
        'city'   => ['required', 'regex:/^[\p{L}\s]+$/u'],
        'mobile' => ['required', 'digits:10'],
        'photo'  => ['nullable', 'image', 'max:200'], // max:200 KB
    ]);

    $data = $request->only('name', 'city', 'mobile');

    if ($request->hasFile('photo')) {
        // ✅ Ensure directory exists
        Storage::makeDirectory('public/sthayi_sampati_sanwardhan_samiti');

        $photo = $request->file('photo');
        $name  = Str::uuid() . '.' . $photo->getClientOriginalExtension();
        $photo->storeAs('public/sthayi_sampati_sanwardhan_samiti', $name);
        $data['photo'] = '/storage/sthayi_sampati_sanwardhan_samiti/' . $name;

    }

    $entry = SthayiSampatiSanwardhanSamiti::create($data);
    return response()->json($entry, 201);
}

public function update(Request $request, $id)
{
    $entry = SthayiSampatiSanwardhanSamiti::findOrFail($id);

    $request->validate([
        'name'   => ['required', 'regex:/^[\p{L}\s]+$/u'],
        'city'   => ['required', 'regex:/^[\p{L}\s]+$/u'],
        'mobile' => ['required', 'digits:10'],
        'photo'  => ['nullable', 'image', 'max:200'], // max:200 KB
    ]);

    $data = $request->only('name', 'city', 'mobile');

    if ($request->hasFile('photo')) {
        // ✅ Delete old photo if exists
        if ($entry->photo && Storage::exists(str_replace('storage/', 'public/', $entry->photo))) {
            Storage::delete(str_replace('storage/', 'public/', $entry->photo));
        }

        // ✅ Ensure directory exists
        Storage::makeDirectory('public/sthayi_sampati_sanwardhan_samiti');

        $photo = $request->file('photo');
        $name  = Str::uuid() . '.' . $photo->getClientOriginalExtension();
        $photo->storeAs('public/sthayi_sampati_sanwardhan_samiti', $name);
      $data['photo'] = '/storage/sthayi_sampati_sanwardhan_samiti/' . $name;
    }

    $entry->update($data);
    return response()->json($entry);
}

    public function destroy($id)
    {
        $entry = SthayiSampatiSanwardhanSamiti::findOrFail($id);
        if ($entry->photo) {
            Storage::delete(str_replace('storage/', 'public/', $entry->photo));
        }
        $entry->delete();
        return response()->json(['message' => 'Deleted']);
    }

    public function show($id)
    {
        return response()->json(SthayiSampatiSanwardhanSamiti::findOrFail($id));
    }
}
