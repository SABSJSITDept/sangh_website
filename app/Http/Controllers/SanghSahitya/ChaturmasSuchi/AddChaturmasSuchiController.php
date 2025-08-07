<?php

namespace App\Http\Controllers\SanghSahitya\ChaturmasSuchi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SanghSahitya\ChaturmasSuchi;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AddChaturmasSuchiController extends Controller
{
    public function index()
    {
        return ChaturmasSuchi::orderBy('year', 'desc')->get();
    }

public function store(Request $request)
{
    $request->validate([
        'year' => 'required|numeric|min:2020|max:' . date('Y') . '|unique:chaturmas_suchiya,year',
        'pdf' => 'required|mimes:pdf|max:2048',
    ]);

    $pdfPath = $request->file('pdf')->store('chaturmas_suchi', 'public');

    ChaturmasSuchi::create([
        'year' => $request->year,
        'pdf' => '/storage/' . $pdfPath,
    ]);

    return response()->json(['success' => true]);
}


    public function update(Request $request, $id)
{
    $request->validate([
        'year' => 'required|numeric|min:2020|max:' . date('Y') . '|unique:chaturmas_suchiya,year,' . $id,
        'pdf' => 'nullable|mimes:pdf|max:2048',
    ]);

    $item = ChaturmasSuchi::findOrFail($id);
    $pdfPath = $item->pdf;

    if ($request->hasFile('pdf')) {
        // Delete old file
        $oldFile = str_replace('/storage/', '', $item->pdf);
        Storage::disk('public')->delete($oldFile);

        // Store new file
        $pdfPath = '/storage/' . $request->file('pdf')->store('chaturmas_suchi', 'public');
    }

    $item->update([
        'year' => $request->year,
        'pdf' => $pdfPath,
    ]);

    return response()->json(['success' => true]);
}



    public function destroy($id)
    {
        $item = ChaturmasSuchi::findOrFail($id);
        $file = str_replace('/storage/', '', $item->pdf);
        Storage::disk('public')->delete($file);
        $item->delete();

        return response()->json(['success' => true]);
    }
}
