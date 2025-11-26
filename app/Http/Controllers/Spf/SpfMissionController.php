<?php

namespace App\Http\Controllers\Spf;

use App\Http\Controllers\Controller;
use App\Models\Spf\SpfMission;
use Illuminate\Http\Request;

class SpfMissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'data' => SpfMission::all()
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
        $mission = SpfMission::create([
            'content' => $request->input('content'),
        ]);
        return response()->json($mission, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(SpfMission $spfMission)
    {
        return response()->json($spfMission);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SpfMission $spfMission)
    {
        $request->validate([
            'content' => 'required|string',
        ]);
        $spfMission->update([
            'content' => $request->input('content'),
        ]);
        return response()->json($spfMission);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SpfMission $spfMission)
    {
        $spfMission->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}
