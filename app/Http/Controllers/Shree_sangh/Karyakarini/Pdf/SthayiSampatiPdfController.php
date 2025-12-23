<?php

namespace App\Http\Controllers\Shree_sangh\Karyakarini\Pdf;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShreeSangh\Karyakarini\Pdf\SthayiSampatiPdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SthayiSampatiPdfController extends Controller
{
    public function index()
    {
        $pdfs = SthayiSampatiPdf::orderBy('session', 'desc')->orderBy('created_at', 'desc')->get();
        return response()->json($pdfs);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'session' => 'required|string',
            'pdf' => 'required|file|mimes:pdf|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->only(['name', 'session']);

        if ($request->hasFile('pdf')) {
            $pdfFile = $request->file('pdf');
            $filename = 'karyakarini/' . uniqid() . '_' . time() . '.pdf';
            $pdfFile->storeAs('public/' . dirname($filename), basename($filename));
            $data['pdf'] = $filename;
        }

        $sthayiSampatiPdf = SthayiSampatiPdf::create($data);

        return response()->json([
            'message' => 'PDF successfully uploaded!',
            'data' => $sthayiSampatiPdf
        ], 201);
    }

    public function show(string $id)
    {
        $pdf = SthayiSampatiPdf::findOrFail($id);
        return response()->json($pdf);
    }

    public function update(Request $request, string $id)
    {
        $sthayiSampatiPdf = SthayiSampatiPdf::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'session' => 'required|string',
            'pdf' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->only(['name', 'session']);

        if ($request->hasFile('pdf')) {
            if ($sthayiSampatiPdf->pdf) {
                Storage::disk('public')->delete($sthayiSampatiPdf->pdf);
            }

            $pdfFile = $request->file('pdf');
            $filename = 'karyakarini/' . uniqid() . '_' . time() . '.pdf';
            $pdfFile->storeAs('public/' . dirname($filename), basename($filename));
            $data['pdf'] = $filename;
        }

        $sthayiSampatiPdf->update($data);

        return response()->json([
            'message' => 'PDF successfully updated!',
            'data' => $sthayiSampatiPdf
        ]);
    }

    public function destroy(string $id)
    {
        $sthayiSampatiPdf = SthayiSampatiPdf::findOrFail($id);

        if ($sthayiSampatiPdf->pdf) {
            Storage::disk('public')->delete($sthayiSampatiPdf->pdf);
        }

        $sthayiSampatiPdf->delete();

        return response()->json(['message' => 'PDF deleted successfully!']);
    }
}
