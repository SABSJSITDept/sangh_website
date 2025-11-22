<?php

namespace App\Http\Controllers\Spf;

use App\Http\Controllers\Controller;
use App\Models\Spf\SpfAbout;
use Illuminate\Http\Request;

class SpfAboutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'data' => SpfAbout::all()
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
        $about = SpfAbout::create([
            'content' => $request->content,
        ]);
        return response()->json($about, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(SpfAbout $spfAbout)
    {
        return response()->json($spfAbout);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SpfAbout $spfAbout)
    {
        $request->validate([
            'content' => 'required|string',
        ]);
        $spfAbout->update([
            'content' => $request->content,
        ]);
        return response()->json($spfAbout);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SpfAbout $spfAbout)
    {
        $spfAbout->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}
