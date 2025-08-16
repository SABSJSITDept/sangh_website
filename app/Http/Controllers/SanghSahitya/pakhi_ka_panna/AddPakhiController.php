<?php

namespace App\Http\Controllers\SanghSahitya\pakhi_ka_panna;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SanghSahitya\Pakhi;
use Illuminate\Support\Facades\Storage;

class AddPakhiController extends Controller
{
    public function index()
    {
        return response()->json(
            Pakhi::orderBy('year', 'desc')->get()
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'year' => 'required|digits:4|integer|min:2020|max:' . date('Y'),
            'pdf' => 'required|mimes:pdf|max:2048', // 2 MB
        ]);

        $pdfPath = $request->file('pdf')->store('pakhi', 'public');

        $pakhi = Pakhi::create([
            'year' => $request->year,
            'pdf' => '/storage/' . $pdfPath,
        ]);

        return response()->json(['success' => true, 'data' => $pakhi]);
    }

    public function update(Request $request, $id)
{
    $pakhi = Pakhi::findOrFail($id);

    $request->validate([
        'year' => 'required|digits:4|integer|min:2020|max:' . date('Y'),
        'pdf' => 'nullable|mimes:pdf|max:2048', // PDF optional in update
    ]);

    // अगर नया PDF दिया है तो पुराना delete करके नया save करो
    if ($request->hasFile('pdf')) {
        if ($pakhi->pdf) {
            $filePath = str_replace('/storage/', '', $pakhi->pdf);
            Storage::disk('public')->delete($filePath);
        }
        $pdfPath = $request->file('pdf')->store('pakhi', 'public');
        $pakhi->pdf = '/storage/' . $pdfPath;
    }

    $pakhi->year = $request->year;
    $pakhi->save();

    return response()->json(['success' => true, 'data' => $pakhi]);
}


    public function destroy($id)
    {
        $pakhi = Pakhi::findOrFail($id);

        if ($pakhi->pdf) {
            $filePath = str_replace('/storage/', '', $pakhi->pdf);
            Storage::disk('public')->delete($filePath);
        }

        $pakhi->delete();

        return response()->json(['success' => true]);
    }
}
