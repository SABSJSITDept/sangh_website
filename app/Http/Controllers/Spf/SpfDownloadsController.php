<?php

namespace App\Http\Controllers\Spf;

use App\Http\Controllers\Controller;
use App\Models\Spf\SpfDownloads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SpfDownloadsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $downloadsList = SpfDownloads::latest()->get();
        return response()->json([
            'success' => true,
            'data' => $downloadsList
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboards.spf.spfdownloads');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120' // 5MB = 5120KB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $filePath = null;
            if ($request->hasFile('file')) {
                $filePath = $request->file('file')->store('spf_downloads', 'public');
            }

            $download = SpfDownloads::create([
                'title' => $request->title,
                'file' => $filePath
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Download created successfully',
                'data' => $download
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating download: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $download = SpfDownloads::find($id);

        if (!$download) {
            return response()->json([
                'success' => false,
                'message' => 'Download not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $download
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $download = SpfDownloads::find($id);
        return view('dashboards.spf.spfdownloads', compact('download'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $download = SpfDownloads::find($id);

        if (!$download) {
            return response()->json([
                'success' => false,
                'message' => 'Download not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120' // 5MB = 5120KB
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

            if ($request->hasFile('file')) {
                $file = $request->file('file');

                // Check if file is valid
                if (!$file->isValid()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'File upload failed. Please try again.'
                    ], 422);
                }

                // Delete old file
                if ($download->file) {
                    Storage::disk('public')->delete($download->file);
                }

                // Store new file
                $filePath = $file->store('spf_downloads', 'public');

                if (!$filePath) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Failed to save file'
                    ], 500);
                }

                $data['file'] = $filePath;
            }

            $download->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Download updated successfully',
                'data' => $download
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating download: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $download = SpfDownloads::find($id);

        if (!$download) {
            return response()->json([
                'success' => false,
                'message' => 'Download not found'
            ], 404);
        }

        try {
            // Delete file
            if ($download->file) {
                Storage::disk('public')->delete($download->file);
            }

            $download->delete();

            return response()->json([
                'success' => true,
                'message' => 'Download deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting download: ' . $e->getMessage()
            ], 500);
        }
    }
}
