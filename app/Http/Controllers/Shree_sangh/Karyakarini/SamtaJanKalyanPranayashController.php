<?php

namespace App\Http\Controllers\Shree_sangh\Karyakarini;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShreeSangh\Karyakarini\SamtaJanKalyanPranayash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class SamtaJanKalyanPranayashController extends Controller
{
    public function index()
    {
        return SamtaJanKalyanPranayash::all();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'city' => 'required|string',
            'mobile' => 'required|string',
            'photo' => 'required|image|max:200', // max 200 KB
            'session' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = $request->only(['name', 'city', 'mobile']);
        $data['photo'] = $request->file('photo')->store('samta_jan_kalyan_pranayash', 'public');
        $data['session'] = $request->input('session', '2025-27');

        $item = SamtaJanKalyanPranayash::create($data);

        return response()->json($item, 201);
    }

    public function update(Request $request, $id)
    {
        $item = SamtaJanKalyanPranayash::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'city' => 'required|string',
            'mobile' => 'required|string',
            'photo' => 'nullable|image|max:200', // optional but max 200 KB
            'session' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = $request->only(['name', 'city', 'mobile', 'session']);

        if ($request->hasFile('photo')) {
            if ($item->photo) {
                Storage::disk('public')->delete($item->photo);
            }
            $data['photo'] = $request->file('photo')->store('samta_jan_kalyan_pranayash', 'public');
        }

        $item->update($data);

        return response()->json(['message' => 'Updated successfully']);
    }
    public function destroy($id)
    {
        $item = SamtaJanKalyanPranayash::findOrFail($id);

        if ($item->photo) {
            Storage::disk('public')->delete($item->photo);
        }

        $item->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }

    public function show($id)
    {
        return SamtaJanKalyanPranayash::findOrFail($id);
    }
}
