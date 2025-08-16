<?php
namespace App\Http\Controllers\Shree_sangh\Karyakarini;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShreeSangh\Karyakarini\KaryasamitiSadasya;
use App\Models\Aanchal;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class KaryasamitiSadasyaController extends Controller
{
    public function index()
    {
        $priorityOrder = [1,2,3,4,5,6,7,8,9,10,11,12];
        $data = KaryasamitiSadasya::with('aanchal')->get();
        $sorted = $data->sortBy(function ($item) use ($priorityOrder) {
            return array_search($item->aanchal_id, $priorityOrder) ?? 999;
        })->values();
        return response()->json($sorted);
    }

    public function show($id)
    {
        $data = KaryasamitiSadasya::with('aanchal')->findOrFail($id);
        return response()->json($data);
    }



public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name'       => 'required|string|max:255',
        'city'       => 'required|string|max:255',
        'aanchal_id' => 'required|exists:aanchal,id',
        'mobile'     => 'required|string|max:15',
        'photo'      => 'nullable|image|max:200', // ✅ max 200 KB
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    $path = null;
    if ($request->hasFile('photo')) {
        $path = $request->file('photo')->store('karyasamiti_sadasya', 'public');
    }

    $data = KaryasamitiSadasya::create([
        'name'       => $request->name,
        'city'       => $request->city,
        'aanchal_id' => $request->aanchal_id,
        'mobile'     => $request->mobile,
        'photo'      => $path
    ]);

    return response()->json($data);
}


public function update(Request $request, $id)
{
    $data = KaryasamitiSadasya::findOrFail($id);

    $validator = Validator::make($request->all(), [
        'name'       => 'required|string|max:255',
        'city'       => 'required|string|max:255',
        'aanchal_id' => 'required|exists:aanchal,id',
        'mobile'     => 'required|string|max:15',
        'photo'      => 'nullable|image|max:200', // ✅ max 200 KB
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    if ($request->hasFile('photo')) {
        if ($data->photo && Storage::disk('public')->exists($data->photo)) {
            Storage::disk('public')->delete($data->photo);
        }
        $data->photo = $request->file('photo')->store('karyasamiti_sadasya', 'public');
    }

    $data->update([
        'name'       => $request->name,
        'city'       => $request->city,
        'aanchal_id' => $request->aanchal_id,
        'mobile'     => $request->mobile,
        'photo'      => $data->photo
    ]);

    return response()->json($data);
}

    public function destroy($id)
    {
        $data = KaryasamitiSadasya::findOrFail($id);
        if ($data->photo && Storage::disk('public')->exists($data->photo)) {
            Storage::disk('public')->delete($data->photo);
        }
        $data->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
