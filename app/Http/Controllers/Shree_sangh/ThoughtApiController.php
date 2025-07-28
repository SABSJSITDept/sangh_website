<?php

namespace App\Http\Controllers\Shree_sangh;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShreeSangh\DailyThought;


class ThoughtApiController extends Controller
{
    public function index()
{
    // 30 thoughts per page
    $thoughts = DailyThought::latest()->paginate(14);

    return response()->json($thoughts);
}


    public function store(Request $request)
    {
        $request->validate([
            'thought' => 'required|string',
            'date' => 'nullable|date',
        ]);

        $thought = DailyThought::create($request->all());

        return response()->json(['message' => 'Thought added!', 'data' => $thought], 201);
    }

    public function show($id)
    {
        $thought = DailyThought::findOrFail($id);
        return response()->json($thought);
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'thought' => 'required|string',
        'date' => 'nullable|date',
    ]);

    $thought = DailyThought::findOrFail($id);
    $thought->update($request->only(['thought', 'date']));

    return response()->json(['message' => 'विचार अपडेट हुआ!', 'data' => $thought]);
}


    public function destroy($id)
    {
        DailyThought::findOrFail($id)->delete();
        return response()->json(['message' => 'Thought deleted']);
    }

    public function create()
    {
        return view('dashboards.shree_sangh.daily_thoughts');
    }

public function latest()
{
    $latest = \App\Models\ShreeSangh\DailyThought::orderByDesc('created_at')->first();

    if (!$latest) {
        return response()->json(null, 204); // no content
    }

    return response()->json([
        'id' => $latest->id,
        'thought' => $latest->thought,
        'date' => $latest->created_at->format('Y-m-d'),
    ]);
}




}
