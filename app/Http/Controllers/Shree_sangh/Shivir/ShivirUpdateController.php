<?php

// app/Http/Controllers/Shree_sangh/Shivir/ShivirUpdateController.php

namespace App\Http\Controllers\Shree_sangh\Shivir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShreeSangh\Shivir\Shivir;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ShivirUpdateController extends Controller
{
    public function index()
{
    return Shivir::latest()->get(); 
}


    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'date'        => 'required|string',
            'location'    => 'required|string|max:255',
            'description' => 'required|string',
            'photo'       => 'required|image|mimes:jpg,jpeg,png|max:200', // 200KB = 200
        ]);

        if (Shivir::count() >= 10) {
            return response()->json(['error' => 'Maximum 10 records allowed.'], 400);
        }

        $photoPath = $request->file('photo')->store('shivir', 'public');

        $shivir = Shivir::create([
            'title'       => $request->title,
            'date'        => $request->date,
            'location'    => $request->location,
            'description' => $request->description,
            'photo'       => $photoPath,
        ]);

        return response()->json($shivir);
    }

    public function show($id)
    {
        return Shivir::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $shivir = Shivir::findOrFail($id);

        $request->validate([
            'title'       => 'required|string|max:255',
            'date'        => 'required|string',
            'location'    => 'required|string|max:255',
            'description' => 'required|string',
            'photo'       => 'nullable|image|mimes:jpg,jpeg,png|max:200',
        ]);

        if ($request->hasFile('photo')) {
            Storage::disk('public')->delete($shivir->photo);
            $photoPath = $request->file('photo')->store('shivir', 'public');
            $shivir->photo = $photoPath;
        }

        $shivir->update($request->only(['title', 'date', 'location', 'description']));

        return response()->json($shivir);
    }

    public function destroy($id)
    {
        $shivir = Shivir::findOrFail($id);
        Storage::disk('public')->delete($shivir->photo);
        $shivir->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }
}
