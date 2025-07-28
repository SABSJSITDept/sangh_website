<?php

// app/Http/Controllers/Shree_sangh/Karyakarini/KaryasamitiSadasyaController.php
namespace App\Http\Controllers\Shree_sangh\Karyakarini;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShreeSangh\Karyakarini\KaryasamitiSadasya;
use App\Models\Aanchal;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class KaryasamitiSadasyaController extends Controller
{
    public function index()
    {
        return response()->json(KaryasamitiSadasya::with('aanchal')->get());
    }

public function store(Request $request)
{
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
        'photo'      => $data->photo // use the updated photo path if set
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
