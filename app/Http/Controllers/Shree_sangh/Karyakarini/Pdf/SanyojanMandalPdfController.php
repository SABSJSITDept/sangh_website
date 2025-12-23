<?php

namespace App\Http\Controllers\Shree_sangh\Karyakarini\Pdf;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShreeSangh\Karyakarini\Pdf\SanyojanMandalPdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SanyojanMandalPdfController extends Controller
{
    public function index()
    {
        $pdfs = SanyojanMandalPdf::orderBy('session', 'desc')->orderBy('created_at', 'desc')->get();
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

        $pdf = SanyojanMandalPdf::create($data);
        return response()->json(['message' => 'PDF successfully uploaded!', 'data' => $pdf], 201);
    }

    public function show(string $id)
    {
        return response()->json(SanyojanMandalPdf::findOrFail($id));
    }

    public function update(Request $request, string $id)
    {
        $pdf = SanyojanMandalPdf::findOrFail($id);

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
            if ($pdf->pdf)
                Storage::disk('public')->delete($pdf->pdf);
            $pdfFile = $request->file('pdf');
            $filename = 'karyakarini/' . uniqid() . '_' . time() . '.pdf';
            $pdfFile->storeAs('public/' . dirname($filename), basename($filename));
            $data['pdf'] = $filename;
        }

        $pdf->update($data);
        return response()->json(['message' => 'PDF successfully updated!', 'data' => $pdf]);
    }

    public function destroy(string $id)
    {
        $pdf = SanyojanMandalPdf::findOrFail($id);
        if ($pdf->pdf)
            Storage::disk('public')->delete($pdf->pdf);
        $pdf->delete();
        return response()->json(['message' => 'PDF deleted successfully!']);
    }
}
