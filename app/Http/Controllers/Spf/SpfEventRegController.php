<?php

namespace App\Http\Controllers\Spf;

use App\Http\Controllers\Controller;
use App\Models\Spf\SpfEventReg;
use App\Models\Spf\SpfEvents;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SpfEventRegController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $registrations = SpfEventReg::with('event')->latest()->get();
            return response()->json([
                'success' => true,
                'data' => $registrations
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch registrations',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'member_id' => 'nullable|string|max:100',
            'event_id' => 'required|exists:spf_events,id',
            'response' => 'nullable|string|in:yes,no,maybe',
        ], [
            'event_id.required' => 'Please select an event',
            'event_id.exists' => 'Selected event does not exist',
            'response.in' => 'Response must be yes, no, or maybe',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $registration = SpfEventReg::create([
                'member_id' => $request->member_id,
                'event_id' => $request->event_id,
                'response' => $request->response ?? 'yes',
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Registration created successfully',
                'data' => $registration
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create registration',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $registration = SpfEventReg::with('event')->findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $registration
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registration not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'member_id' => 'nullable|string|max:100',
            'event_id' => 'required|exists:spf_events,id',
            'response' => 'nullable|string|in:yes,no,maybe',
        ], [
            'event_id.required' => 'Please select an event',
            'event_id.exists' => 'Selected event does not exist',
            'response.in' => 'Response must be yes, no, or maybe',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $registration = SpfEventReg::findOrFail($id);
            $registration->update([
                'member_id' => $request->member_id,
                'event_id' => $request->event_id,
                'response' => $request->response ?? 'yes',
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Registration updated successfully',
                'data' => $registration
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update registration',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $registration = SpfEventReg::findOrFail($id);
            $registration->delete();
            return response()->json([
                'success' => true,
                'message' => 'Registration deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete registration',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all events for dropdown
     */
    public function getEvents()
    {
        try {
            $events = SpfEvents::select('id', 'title')->orderBy('title', 'asc')->get();
            return response()->json([
                'success' => true,
                'data' => $events
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch events',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
