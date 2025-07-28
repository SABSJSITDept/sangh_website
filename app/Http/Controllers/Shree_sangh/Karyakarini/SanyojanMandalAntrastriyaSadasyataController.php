<?php

namespace App\Http\Controllers\Shree_sangh\Karyakarini;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\ShreeSangh\Karyakarini\SanyojanMandalAntrastriyaSadasyata;

class SanyojanMandalAntrastriyaSadasyataController extends Controller
{
    public function index()
    {
        return SanyojanMandalAntrastriyaSadasyata::all();
    }

    public function store(Request $request)
    {
        // Check if file exists before saving
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('sanyojan_mandal_antrastriya_sadasyata', 'public');
        } else {
            return response()->json(['error' => 'Photo file is required.'], 422);
        }

        $sadasya = SanyojanMandalAntrastriyaSadasyata::create([
            'name'   => $request->name,
            'city'   => $request->city,
            'mobile' => $request->mobile,
            'photo'  => $path,
        ]);

        return response()->json($sadasya, 201);
    }

    public function show(SanyojanMandalAntrastriyaSadasyata $sadasya)
    {
        return response()->json($sadasya);
    }

    public function update(Request $request, SanyojanMandalAntrastriyaSadasyata $sadasya)
    {
        // Update photo if provided
        if ($request->hasFile('photo')) {
            if ($sadasya->photo) {
                Storage::disk('public')->delete($sadasya->photo);
            }
            $path = $request->file('photo')->store('sanyojan_mandal_antrastriya_sadasyata', 'public');
            $sadasya->photo = $path;
        }

        $sadasya->update([
            'name'   => $request->name,
            'city'   => $request->city,
            'mobile' => $request->mobile,
        ]);

        return response()->json($sadasya);
    }

    public function destroy(SanyojanMandalAntrastriyaSadasyata $sadasya)
    {
        if ($sadasya->photo) {
            Storage::disk('public')->delete($sadasya->photo);
        }

        $sadasya->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }
}
