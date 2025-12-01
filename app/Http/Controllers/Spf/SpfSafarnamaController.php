<?php

namespace App\Http\Controllers\Spf;

use App\Http\Controllers\Controller;
use App\Models\Spf\SpfSafarnama;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SpfSafarnamaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $safarnamaList = SpfSafarnama::latest()->get();
        return response()->json([
            'success' => true,
            'data' => $safarnamaList
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboards.spf.safarnama');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'pdf' => 'required|file|mimes:pdf|max:5120' // 5MB = 5120KB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $pdfPath = null;
            if ($request->hasFile('pdf')) {
                $pdfPath = $request->file('pdf')->store('spf_safarnama', 'public');
            }

            $safarnama = SpfSafarnama::create([
                'title' => $request->title,
                'pdf' => $pdfPath
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Safarnama created successfully',
                'data' => $safarnama
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating safarnama: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $safarnama = SpfSafarnama::find($id);

        if (!$safarnama) {
            return response()->json([
                'success' => false,
                'message' => 'Safarnama not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $safarnama
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $safarnama = SpfSafarnama::find($id);
        return view('dashboards.spf.safarnama', compact('safarnama'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $safarnama = SpfSafarnama::find($id);

        if (!$safarnama) {
            return response()->json([
                'success' => false,
                'message' => 'Safarnama not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'pdf' => 'nullable|file|mimes:pdf|max:5120' // 5MB = 5120KB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = [
                'title' => $request->title
            ];

            if ($request->hasFile('pdf')) {
                $file = $request->file('pdf');
                
                // Check if file is valid
                if (!$file->isValid()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'PDF file upload failed. Please try again.'
                    ], 422);
                }

                // Delete old PDF
                if ($safarnama->pdf) {
                    Storage::disk('public')->delete($safarnama->pdf);
                }

                // Store new PDF
                $pdfPath = $file->store('spf_safarnama', 'public');
                
                if (!$pdfPath) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Failed to save PDF file'
                    ], 500);
                }

                $data['pdf'] = $pdfPath;
            }

            $safarnama->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Safarnama updated successfully',
                'data' => $safarnama
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating safarnama: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $safarnama = SpfSafarnama::find($id);

        if (!$safarnama) {
            return response()->json([
                'success' => false,
                'message' => 'Safarnama not found'
            ], 404);
        }

        try {
            // Delete PDF file
            if ($safarnama->pdf) {
                Storage::disk('public')->delete($safarnama->pdf);
            }

            $safarnama->delete();

            return response()->json([
                'success' => true,
                'message' => 'Safarnama deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting safarnama: ' . $e->getMessage()
            ], 500);
        }
    }
}
