<?php

namespace App\Http\Controllers;

use App\Models\AppVersion;
use Illuminate\Http\Request;

class AppVersionController extends Controller
{
    // ✅ Get latest version
    public function latest()
    {
        $latest = AppVersion::latest()->first();

        if (!$latest) {
            return response()->json(['message' => 'No version found'], 404);
        }

        return response()->json([
            'version_code' => $latest->version_code
        ]);
    }

    // ✅ Store new version
    public function store(Request $request)
    {
        $request->validate([
            'version_code' => 'required|string|max:20',
        ]);

        $version = AppVersion::create([
            'version_code' => $request->version_code,
        ]);

        return response()->json([
            'message' => 'Version added successfully',
            'data' => $version
        ]);
    }

    // ✅ List all versions (optional, for admin)
    public function index()
    {
        return response()->json(AppVersion::latest()->get());
    }

    // ✅ Delete a version (optional)
    public function destroy($id)
    {
        $version = AppVersion::findOrFail($id);
        $version->delete();

        return response()->json(['message' => 'Version deleted successfully']);
    }
}
