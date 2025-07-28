<?php

namespace App\Http\Controllers\Shree_sangh\Karyakarini;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShreeSangh\Karyakarini\VpSec;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class VpSecController extends Controller
{
    public function index()
    {
        return VpSec::all();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'post' => 'required',
            'city' => 'required',
            'aanchal' => 'nullable',
            'mobile' => 'required',
            'photo' => 'nullable|image|max:200', // 200 KB
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = $request->only(['name', 'post', 'city', 'aanchal', 'mobile']);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('vp_sec', 'public');
        }

        $vpSec = VpSec::create($data);

        return response()->json($vpSec, 201);
    }

    public function show($id)
    {
        return VpSec::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $vpSec = VpSec::findOrFail($id);

        $data = $request->only(['name', 'post', 'city', 'aanchal', 'mobile']);

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($vpSec->photo) {
                Storage::disk('public')->delete($vpSec->photo);
            }
            $data['photo'] = $request->file('photo')->store('vp_sec', 'public');
        }

        $vpSec->update($data);

        return response()->json($vpSec);
    }

    public function destroy($id)
    {
        $vpSec = VpSec::findOrFail($id);

        if ($vpSec->photo) {
            Storage::disk('public')->delete($vpSec->photo);
        }

        $vpSec->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }
}
