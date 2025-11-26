<?php

namespace App\Http\Controllers\spf;

use App\Http\Controllers\Controller;
use App\Models\spf\SpfProjects;
use Illuminate\Http\Request;

class SpfProjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = SpfProjects::oldest()->get();
        return response()->json([
            'success' => true,
            'data' => $projects
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $project = SpfProjects::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Project created successfully',
            'data' => $project
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $project = SpfProjects::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $project
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $project = SpfProjects::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $project->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Project updated successfully',
            'data' => $project
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $project = SpfProjects::findOrFail($id);
        $project->delete();

        return response()->json([
            'success' => true,
            'message' => 'Project deleted successfully'
        ]);
    }
}
