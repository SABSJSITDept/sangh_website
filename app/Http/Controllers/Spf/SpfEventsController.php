<?php

namespace App\Http\Controllers\Spf;

use App\Http\Controllers\Controller;
use App\Models\Spf\SpfEvents;
use App\Models\Spf\SpfProjects;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SpfEventsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = SpfEvents::with('project:id,title')->orderBy('date', 'desc')->get();
        return response()->json([
            'success' => true,
            'data' => $events
        ]);
    }

    /**
     * Get all projects for dropdown
     */
    public function getProjects()
    {
        $projects = SpfProjects::select('id', 'title')->get();
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
        $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'time' => 'required|string',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'spf_project_id' => 'required|exists:spf_projects,id',
            'event_reg_start' => 'nullable|date',
            'event_reg_close' => 'nullable|date',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('spf_events', 'public');
        }

        $event = SpfEvents::create([
            'title' => $request->title,
            'date' => $request->date,
            'time' => $request->time,
            'location' => $request->location,
            'description' => $request->description,
            'photo' => $photoPath,
            'spf_project_id' => $request->spf_project_id,
            'event_reg_start' => $request->event_reg_start,
            'event_reg_close' => $request->event_reg_close,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Event created successfully',
            'data' => $event
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $event = SpfEvents::with('project:id,title')->find($id);

        if (!$event) {
            return response()->json([
                'success' => false,
                'message' => 'Event not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $event
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $event = SpfEvents::find($id);

        if (!$event) {
            return response()->json([
                'success' => false,
                'message' => 'Event not found'
            ], 404);
        }

        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'date' => 'sometimes|required|date',
            'time' => 'sometimes|required|string',
            'location' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'photo' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'spf_project_id' => 'nullable|exists:spf_projects,id',
            'event_reg_start' => 'nullable|date',
            'event_reg_close' => 'nullable|date',
        ]);

        if ($request->hasFile('photo')) {
            // Delete old photo
            if ($event->photo) {
                Storage::disk('public')->delete($event->photo);
            }
            $event->photo = $request->file('photo')->store('spf_events', 'public');
        }

        $event->update($request->except('photo'));

        return response()->json([
            'success' => true,
            'message' => 'Event updated successfully',
            'data' => $event
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $event = SpfEvents::find($id);

        if (!$event) {
            return response()->json([
                'success' => false,
                'message' => 'Event not found'
            ], 404);
        }

        // Delete photo from storage
        if ($event->photo) {
            Storage::disk('public')->delete($event->photo);
        }

        $event->delete();

        return response()->json([
            'success' => true,
            'message' => 'Event deleted successfully'
        ]);
    }
}
