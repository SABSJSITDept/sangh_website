<?php

namespace App\Http\Controllers\Shree_sangh\Karyakarini;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShreeSangh\Karyakarini\SthayiSampatiSanwardhanSamiti;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SthayiSampatiSanwardhanSamitiController extends Controller
{
    public function index()
    {
        return SthayiSampatiSanwardhanSamiti::orderByRaw("
        FIELD(post, 'Sanyojak', 'Seh Sanyojak', 'Sanyojan Mandal Sadasy')
    ")->get();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'post' => 'required|string|in:sanyojak,seh sanyojak,sanyojan mandal sadasy',
            'city' => 'required|string|max:255',
            'mobile_number' => 'required|digits:10',
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:200', // 200KB
            'session' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $photoPath = $request->file('photo')->store('sthayi_sampati', 'public');

        $data = SthayiSampatiSanwardhanSamiti::create([
            'name' => $request->name,
            'post' => $request->post,
            'city' => $request->city,
            'mobile_number' => $request->mobile_number,
            'photo' => '/storage/' . $photoPath,
            'session' => $request->input('session', '2025-27')
        ]);

        return response()->json($data, 201);
    }

    public function update(Request $request, $id)
    {
        $item = SthayiSampatiSanwardhanSamiti::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'post' => 'required|string|in:sanyojak,seh sanyojak,sanyojan mandal sadasy',
            'city' => 'required|string|max:255',
            'mobile_number' => 'required|digits:10',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:200',
            'session' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->hasFile('photo')) {
            if ($item->photo && file_exists(public_path($item->photo))) {
                unlink(public_path($item->photo));
            }
            $photoPath = $request->file('photo')->store('sthayi_sampati', 'public');
            $item->photo = '/storage/' . $photoPath;
        }

        $item->update($request->only(['name', 'post', 'city', 'mobile_number', 'session']));

        return response()->json($item);
    }

    public function destroy($id)
    {
        $item = SthayiSampatiSanwardhanSamiti::findOrFail($id);

        if ($item->photo && file_exists(public_path($item->photo))) {
            unlink(public_path($item->photo));
        }

        $item->delete();
        return response()->json(['message' => 'Deleted Successfully']);
    }
}
