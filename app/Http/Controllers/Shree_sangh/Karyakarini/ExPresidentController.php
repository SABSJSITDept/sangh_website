<?php

namespace App\Http\Controllers\Shree_sangh\Karyakarini;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShreeSangh\Karyakarini\ExPresident;
use Illuminate\Support\Facades\Storage;

class ExPresidentController extends Controller
{
    // ✅ 1. Blade view for managing entries
    public function index()
    {
        return view('dashboards.shree_sangh.karyakarini.ex_president');
    }

    // ✅ 2. Get all entries as JSON
    public function all()
    {
        $data = ExPresident::all();
        return response()->json($data);
    }

    // ✅ 3. Store new entry via API
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'place' => 'required|string',
            'karaykal' => 'required|string',
            'photo' => 'required|image|max:2048', // only image, max 2MB
        ]);

        $path = $request->file('photo')->store('ex_presidents', 'public');

        $president = ExPresident::create([
            'name' => $request->name,
            'place' => $request->place,
            'karaykal' => $request->karaykal,
            'photo' => $path,
        ]);

        return response()->json(['message' => 'Added Successfully', 'data' => $president]);
    }

    // ✅ 4. Update entry
    public function update(Request $request, $id)
    {
        $president = ExPresident::findOrFail($id);

        $request->validate([
            'name' => 'required|string',
            'place' => 'required|string',
            'karaykal' => 'required|string',
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($president->photo) {
                Storage::disk('public')->delete($president->photo);
            }

            $president->photo = $request->file('photo')->store('ex_presidents', 'public');
        }

        $president->update([
            'name' => $request->name,
            'place' => $request->place,
            'karaykal' => $request->karaykal,
        ]);

        return response()->json(['message' => 'Updated Successfully', 'data' => $president]);
    }

    // ✅ 5. Delete entry
    public function destroy($id)
    {
        $president = ExPresident::findOrFail($id);
        Storage::disk('public')->delete($president->photo);
        $president->delete();

        return response()->json(['message' => 'Deleted Successfully']);
    }
}
