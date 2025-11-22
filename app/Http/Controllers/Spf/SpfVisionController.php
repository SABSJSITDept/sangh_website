<?php

namespace App\Http\Controllers\Spf;

use App\Http\Controllers\Controller;
use App\Models\Spf\SpfVision;
use Illuminate\Http\Request;

class SpfVisionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'data' => SpfVision::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
        ]);
        $vision = SpfVision::create([
            'content' => $request->content,
        ]);
        return response()->json($vision, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(SpfVision $spfVision)
    {
        return response()->json($spfVision);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SpfVision $spfVision)
    {
        $request->validate([
            'content' => 'required|string',
        ]);
        $spfVision->update([
            'content' => $request->content,
        ]);
        return response()->json($spfVision);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SpfVision $spfVision)
    {
        $spfVision->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}
