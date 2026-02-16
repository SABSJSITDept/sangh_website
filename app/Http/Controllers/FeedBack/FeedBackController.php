<?php

namespace App\Http\Controllers\FeedBack;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Feedback;
use Illuminate\Support\Facades\Validator;

class FeedBackController extends Controller
{
    public function index()
    {
        return view('feedback.index');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'feedback' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $feedback = Feedback::create([
                'name' => $request->name,
                'mobile' => $request->mobile,
                'email' => $request->email,
                'feedback' => $request->feedback,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Feedback submitted successfully!',
                'data' => $feedback
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong. Please try again later.'
            ], 500);
        }
    }
}
