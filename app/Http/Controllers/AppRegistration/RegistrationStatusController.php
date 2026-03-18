<?php

namespace App\Http\Controllers\AppRegistration;

use App\Http\Controllers\Controller;
use App\Models\AppRegistration\RegistrationStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegistrationStatusController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('AppRegistration.registration_status');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $statuses = RegistrationStatus::all();
            return response()->json([
                'success' => true,
                'message' => 'Registration statuses retrieved successfully',
                'data' => $statuses
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving registration statuses: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Check if already 2 entries exist
        $count = RegistrationStatus::count();
        if ($count >= 2) {
            return response()->json([
                'success' => false,
                'message' => 'Only 2 statuses (enable and disable) are allowed'
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:enable,disable|unique:registration_status,status',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $status = RegistrationStatus::create([
                'status' => $request->status
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Registration status created successfully',
                'data' => $status
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating registration status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $status = RegistrationStatus::find($id);

            if (!$status) {
                return response()->json([
                    'success' => false,
                    'message' => 'Registration status not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Registration status retrieved successfully',
                'data' => $status
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving registration status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $status = RegistrationStatus::find($id);

        if (!$status) {
            return response()->json([
                'success' => false,
                'message' => 'Registration status not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:enable,disable|unique:registration_status,status,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $status->update([
                'status' => $request->status
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Registration status updated successfully',
                'data' => $status
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating registration status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $status = RegistrationStatus::find($id);

            if (!$status) {
                return response()->json([
                    'success' => false,
                    'message' => 'Registration status not found'
                ], 404);
            }

            $status->delete();

            return response()->json([
                'success' => true,
                'message' => 'Registration status deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting registration status: ' . $e->getMessage()
            ], 500);
        }
    }
}
