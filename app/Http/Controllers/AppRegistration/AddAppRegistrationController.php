<?php

namespace App\Http\Controllers\AppRegistration;

use App\Http\Controllers\Controller;
use App\Models\AppRegistration\AppRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AddAppRegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $registrations = AppRegistration::all();
        return response()->json([
            'success' => true,
            'message' => 'Registrations retrieved successfully',
            'data' => $registrations
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('AppRegistration.appregistration');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'mobile' => 'required|string|max:20|unique:mrm_app',
            'email_address' => 'nullable|email|unique:mrm_app',
            'gender' => 'nullable|in:Male,Female,Other',
            'marital_status' => 'nullable|in:Single,Married,Divorced,Widowed',
            'birth_day' => 'nullable|date',
            'relation_id' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $registration = AppRegistration::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Registration created successfully',
                'data' => $registration
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating registration: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $registration = AppRegistration::find($id);

        if (!$registration) {
            return response()->json([
                'success' => false,
                'message' => 'Registration not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Registration retrieved successfully',
            'data' => $registration
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $registration = AppRegistration::find($id);

        if (!$registration) {
            return response()->json([
                'success' => false,
                'message' => 'Registration not found'
            ], 404);
        }

        return view('AppRegistration.appregistration', ['registration' => $registration]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $registration = AppRegistration::find($id);

        if (!$registration) {
            return response()->json([
                'success' => false,
                'message' => 'Registration not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'mobile' => 'required|string|max:20|unique:mrm_app,mobile,' . $id,
            'email_address' => 'nullable|email|unique:mrm_app,email_address,' . $id,
            'gender' => 'nullable|in:Male,Female,Other',
            'marital_status' => 'nullable|in:Single,Married,Divorced,Widowed',
            'birth_day' => 'nullable|date',
            'relation_id' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $registration->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Registration updated successfully',
                'data' => $registration
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating registration: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $registration = AppRegistration::find($id);

        if (!$registration) {
            return response()->json([
                'success' => false,
                'message' => 'Registration not found'
            ], 404);
        }

        try {
            $registration->delete();

            return response()->json([
                'success' => true,
                'message' => 'Registration deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting registration: ' . $e->getMessage()
            ], 500);
        }
    }
}
