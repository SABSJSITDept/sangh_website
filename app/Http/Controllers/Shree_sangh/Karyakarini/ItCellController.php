<?php

// app/Http/Controllers/ShreeSangh/Karyakarini/ItCellController.php
namespace App\Http\Controllers\Shree_sangh\Karyakarini;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShreeSangh\Karyakarini\ItCell;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ItCellController extends Controller
{
    public function index()
    {
       return response()->json(ItCell::orderBy('priority')->latest()->get());

    }

   public function store(Request $request)
{
    $request->validate([
        'name'     => 'required|string|max:255',
        'post'     => 'required|string|max:255',
        'city'     => 'required|string|max:255',
        'mobile'   => 'required|string|max:20',
        'priority' => 'required|integer|min:1',
        'photo'    => 'nullable|image|max:200',
    ]);

    $photoPath = null;
    if ($request->hasFile('photo')) {
        $photoPath = $request->file('photo')->store('it_cell', 'public');
    }

    $entry = ItCell::create([
        'name'     => $request->name,
        'post'     => $request->post,
        'city'     => $request->city,
        'mobile'   => $request->mobile,
        'priority' => $request->priority,
        'photo'    => $photoPath,
    ]);

    return response()->json($entry);
}


  public function update(Request $request, $id)
{
    $entry = ItCell::findOrFail($id);

    $request->validate([
        'name'     => 'required|string|max:255',
        'post'     => 'required|string|max:255',
        'city'     => 'required|string|max:255',
        'mobile'   => 'required|string|max:20',
        'priority' => 'required|integer|min:1',
        'photo'    => 'nullable|image|max:200',
    ]);

    if ($request->hasFile('photo')) {
        if ($entry->photo) {
            Storage::disk('public')->delete($entry->photo);
        }
        $entry->photo = $request->file('photo')->store('it_cell', 'public');
    }

    $entry->update([
        'name'     => $request->name,
        'post'     => $request->post,
        'city'     => $request->city,
        'mobile'   => $request->mobile,
        'priority' => $request->priority,
    ]);

    return response()->json($entry);
}

    public function destroy($id)
    {
        $entry = ItCell::findOrFail($id);
        if ($entry->photo) {
            Storage::disk('public')->delete($entry->photo);
        }
        $entry->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}
