<?php

namespace App\Http\Controllers\Shree_sangh\Karyakarini\Pdf;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShreeSangh\Karyakarini\Pdf\PravartiSanyojakPdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PravartiSanyojakPdfController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pdfs = PravartiSanyojakPdf::orderBy('session', 'desc')->orderBy('created_at', 'desc')->get();
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

        $pravartiSanyojakPdf = PravartiSanyojakPdf::create($data);

        return response()->json([
            'message' => 'PDF successfully uploaded!',
            'data' => $pravartiSanyojakPdf
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pdf = PravartiSanyojakPdf::findOrFail($id);
        return response()->json($pdf);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $pravartiSanyojakPdf = PravartiSanyojakPdf::findOrFail($id);

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
            if ($pravartiSanyojakPdf->pdf) {
                Storage::disk('public')->delete($pravartiSanyojakPdf->pdf);
            }

            $pdfFile = $request->file('pdf');
            $filename = 'karyakarini/' . uniqid() . '_' . time() . '.pdf';

            // Store in storage/app/public/karyakarini
            $pdfFile->storeAs('public/' . dirname($filename), basename($filename));

            $data['pdf'] = $filename;
        }

        $pravartiSanyojakPdf->update($data);

        return response()->json([
            'message' => 'PDF successfully updated!',
            'data' => $pravartiSanyojakPdf
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pravartiSanyojakPdf = PravartiSanyojakPdf::findOrFail($id);

        // Delete PDF file
        if ($pravartiSanyojakPdf->pdf) {
            Storage::disk('public')->delete($pravartiSanyojakPdf->pdf);
        }

        $pravartiSanyojakPdf->delete();

        return response()->json(['message' => 'PDF deleted successfully!']);
    }
}
