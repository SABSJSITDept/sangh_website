<?php


namespace App\Http\Controllers\Shree_sangh\Karyakarini;

use App\Models\ShreeSangh\Karyakarini\PadhadhikariPrashashanKaryashala;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator; // ✅ Add this line
use Illuminate\Routing\Controller;


class PadhadhikariPrashashanKaryashalaController extends Controller
{
    public function index()
    {
        return response()->json(PadhadhikariPrashashanKaryashala::all());
    }

public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string',
        'pdf'  => 'required|file|mimes:pdf|max:3072' // max 3MB
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'errors' => $validator->errors()
        ], 422);
    }

    $pdfPath = $request->file('pdf')->store('padhadhikari_prashashan_karyashala', 'public');

    $data = PadhadhikariPrashashanKaryashala::create([
        'name' => $request->name,
        'pdf'  => $pdfPath,
    ]);

    return response()->json([
        'status' => true,
        'message' => 'PDF uploaded successfully.',
        'data' => $data
    ], 201);
}


    public function update(Request $request, PadhadhikariPrashashanKaryashala $record)
    {
        $request->validate([
            'name' => 'required|string',
            'pdf'  => 'nullable|file|mimes:pdf|max:2048'
        ]);

        if ($request->hasFile('pdf')) {
            Storage::disk('public')->delete($record->pdf);
            $pdfPath = $request->file('pdf')->store('padhadhikari_prashashan_karyashala', 'public');
            $record->pdf = $pdfPath;
        }

        $record->name = $request->name;
        $record->save();

        return response()->json($record);
    }

    public function destroy(PadhadhikariPrashashanKaryashala $record)
    {
        Storage::disk('public')->delete($record->pdf);
        $record->delete();

        return response()->json(['message' => 'Deleted']);
    }

    public function show(PadhadhikariPrashashanKaryashala $record)
    {
        return response()->json($record);
    }
}
