<?php

namespace App\Http\Controllers\Shree_sangh\Karyakarini\Pdf;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShreeSangh\Karyakarini\Pdf\VpSecPdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class VpSecPdfController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pdfs = VpSecPdf::orderBy('session', 'desc')->orderBy('created_at', 'desc')->get();
        return response()->json($pdfs);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'session' => 'required|string',
            'pdf' => 'required|file|mimes:pdf|max:5120', // 5MB max
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->only(['name', 'session']);

        // Handle PDF upload
        if ($request->hasFile('pdf')) {
            $pdfFile = $request->file('pdf');
            $filename = 'karyakarini/' . uniqid() . '_' . time() . '.pdf';

            // Store in storage/app/public/karyakarini
            $pdfFile->storeAs('public/' . dirname($filename), basename($filename));

            $data['pdf'] = $filename;
        }

        $vpSecPdf = VpSecPdf::create($data);

        return response()->json([
            'message' => 'PDF successfully uploaded!',
            'data' => $vpSecPdf
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pdf = VpSecPdf::findOrFail($id);
        return response()->json($pdf);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $vpSecPdf = VpSecPdf::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'session' => 'required|string',
            'pdf' => 'nullable|file|mimes:pdf|max:5120', // 5MB max
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->only(['name', 'session']);

        // Handle PDF upload if new file is provided
        if ($request->hasFile('pdf')) {
            // Delete old PDF
            if ($vpSecPdf->pdf) {
                Storage::disk('public')->delete($vpSecPdf->pdf);
            }

            $pdfFile = $request->file('pdf');
            $filename = 'karyakarini/' . uniqid() . '_' . time() . '.pdf';

            // Store in storage/app/public/karyakarini
            $pdfFile->storeAs('public/' . dirname($filename), basename($filename));

            $data['pdf'] = $filename;
        }

        $vpSecPdf->update($data);

        return response()->json([
            'message' => 'PDF successfully updated!',
            'data' => $vpSecPdf
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $vpSecPdf = VpSecPdf::findOrFail($id);

        // Delete PDF file
        if ($vpSecPdf->pdf) {
            Storage::disk('public')->delete($vpSecPdf->pdf);
        }

        $vpSecPdf->delete();

        return response()->json(['message' => 'PDF deleted successfully!']);
    }
}
