<?php

// app/Http/Controllers/Shree_sangh/Karyakarini/PravartiSanyojakController.php
namespace App\Http\Controllers\Shree_sangh\Karyakarini;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShreeSangh\Karyakarini\PravartiSanyojak;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PravartiSanyojakController extends Controller
{
public function index()
{
    $postPriority = ['संयोजक', 'संयोजिका', 'सह संयोजक', 'संयोजन मण्डल सदस्य'];

    // Fetch all with relation
    $data = PravartiSanyojak::with('pravarti')->get();

    // Group by Pravarti
    $grouped = $data->groupBy(function ($item) {
        return $item->pravarti->name ?? 'अन्य';
    });

    // Sort each group by post priority
    $sortedGrouped = $grouped->map(function ($group) use ($postPriority) {
        return $group->sortBy(function ($item) use ($postPriority) {
            return array_search($item->post, $postPriority) ?? 999;
        })->values(); // Reset index
    });

    return response()->json($sortedGrouped);
}




public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name'        => 'required',
        'post'        => 'required',
        'city'        => 'required',
        'pravarti_id' => 'required|exists:pravarti,id',
        'mobile'      => 'required|digits:10',
        'photo'       => 'required|image|max:200' // Max 200KB
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

   // ❗ Check for existing संयोजक / संयोजिका in same प्रवर्ती
if ($request->post === 'संयोजक' || $request->post === 'संयोजिका') {
    $exists = PravartiSanyojak::where('pravarti_id', $request->pravarti_id)
        ->whereIn('post', ['संयोजक', 'संयोजिका'])
        ->exists();

    if ($exists) {
        return response()->json([
            'error' => '❌ इस प्रवर्ती के लिए पहले से संयोजक/संयोजिका मौजूद है।'
        ], 422);
    }
}


    $path = $request->file('photo')->store('pravarti_sanyojak', 'public');

    PravartiSanyojak::create(
        $request->only(['name', 'post', 'city', 'pravarti_id', 'mobile']) + ['photo' => $path]
    );

    return response()->json(['success' => true]);
}

public function update(Request $request, $id)
{
    $data = PravartiSanyojak::findOrFail($id);

    $validator = Validator::make($request->all(), [
        'name'        => 'required',
        'post'        => 'required',
        'city'        => 'required',
        'pravarti_id' => 'required|exists:pravarti,id',
        'mobile'      => 'required|digits:10',
        'photo'       => 'nullable|image|max:200' // Max 200KB
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    // ❗ Check for existing संयोजक / संयोजिका in same प्रवर्ती (excluding current record)
    if (in_array($request->post, ['संयोजक', 'संयोजिका'])) {
        $exists = PravartiSanyojak::where('pravarti_id', $request->pravarti_id)
            ->whereIn('post', ['संयोजक', 'संयोजिका'])
            ->where('id', '!=', $id) // exclude current record
            ->exists();

        if ($exists) {
            return response()->json([
                'error' => '❌ इस प्रवर्ती के लिए पहले से संयोजक/संयोजिका मौजूद है।'
            ], 422);
        }
    }

    // Update photo if new one uploaded
    if ($request->hasFile('photo')) {
        if ($data->photo) {
            Storage::disk('public')->delete($data->photo);
        }
        $data->photo = $request->file('photo')->store('pravarti_sanyojak', 'public');
    }

    $data->update($request->only(['name', 'post', 'city', 'pravarti_id', 'mobile']));

    return response()->json(['success' => true]);
}


    public function destroy($id)
    {
        $data = PravartiSanyojak::findOrFail($id);
        Storage::disk('public')->delete($data->photo);
        $data->delete();

        return response()->json(['success' => true]);
    }
}

