<?php

namespace App\Http\Controllers\Shree_sangh\Karyakarini;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\ShreeSangh\Karyakarini\SanyojanMandalAntrastriyaSadasyata;

class SanyojanMandalAntrastriyaSadasyataController extends Controller
{
    public function index()
    {
        return SanyojanMandalAntrastriyaSadasyata::all();
    }

   public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name'   => 'required|string',
        'city'   => 'required|string',
        'mobile' => 'required|string',
        'photo'  => 'required|image|max:200', // max size in KB
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    $path = $request->file('photo')->store('sanyojan_mandal_antrastriya_sadasyata', 'public');

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
    $validator = Validator::make($request->all(), [
        'name'   => 'required|string',
        'city'   => 'required|string',
        'mobile' => 'required|string',
        'photo'  => 'nullable|image|max:200', // optional but max 200 KB
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

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
