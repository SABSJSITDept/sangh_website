<?php

namespace App\Http\Controllers\Shree_sangh\Karyakarini;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShreeSangh\Karyakarini\SamtaJanKalyanPranayash;
use Illuminate\Support\Facades\Storage;

class SamtaJanKalyanPranayashController extends Controller
{
    public function index()
    {
        return SamtaJanKalyanPranayash::all();
    }

    public function store(Request $request)
    {
        $data = $request->only(['name', 'city', 'mobile']);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('samta_jan_kalyan_pranayash', 'public');
        }

        return SamtaJanKalyanPranayash::create($data);
    }

    public function update(Request $request, $id)
    {
        $item = SamtaJanKalyanPranayash::findOrFail($id);
        $data = $request->only(['name', 'city', 'mobile']);

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
