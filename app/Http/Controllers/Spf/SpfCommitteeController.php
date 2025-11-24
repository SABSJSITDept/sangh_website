<?php

namespace App\Http\Controllers\Spf;

use App\Http\Controllers\Controller;
use App\Models\Spf\SpfCommittee;
use Illuminate\Http\Request;

class SpfCommitteeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $members = SpfCommittee::with('anchal')->get();
        return response()->json(['data' => $members], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'post' => 'required|string|max:255',
            'anchal_id' => 'nullable|exists:anchal,id',
        ]);

        $member = SpfCommittee::create($validated);
        return response()->json(['data' => $member, 'message' => 'Member created successfully'], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SpfCommittee $spfCommittee)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'post' => 'required|string|max:255',
            'anchal_id' => 'nullable|exists:anchal,id',
        ]);

        $spfCommittee->update($validated);
        return response()->json(['data' => $spfCommittee, 'message' => 'Member updated successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SpfCommittee $spfCommittee)
    {
        $spfCommittee->delete();
        return response()->json(['message' => 'Member deleted successfully'], 200);
    }

    public function getAdvisoryBoard()
    {
        $members = SpfCommittee::where('post', 'Advisory Board')->get();
        return response()->json(['data' => $members], 200);
    }

    public function getCoreCommittee()
    {
        $members = SpfCommittee::where('post', 'Core Committee')->get();
        return response()->json(['data' => $members], 200);
    }

    public function getAnchalCoordinators($anchalId)
    {
        $members = SpfCommittee::where('post', 'Anchal Coordinators')
            ->where('anchal_id', $anchalId)
            ->get();
        return response()->json(['data' => $members], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(SpfCommittee $spfCommittee)
    {
        return response()->json(['data' => $spfCommittee], 200);
    }
}
