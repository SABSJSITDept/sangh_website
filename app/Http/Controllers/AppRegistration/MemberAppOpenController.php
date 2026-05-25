<?php

namespace App\Http\Controllers\AppRegistration;

use App\Http\Controllers\Controller;
use App\Models\AppRegistration\MemberAppOpen;
use App\Models\AppRegistration\AppRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class MemberAppOpenController extends Controller
{
    /**
     * Store a newly created app open record.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'member_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $log = MemberAppOpen::create([
                'member_id' => $request->member_id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'App open logged successfully',
                'data' => $log
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error logging app open: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Retrieve members who opened the app today (or on a specific date).
     */
    public function getTodayOpens(Request $request)
    {
        $dateStr = $request->query('date', Carbon::today()->toDateString());

        try {
            $date = Carbon::parse($dateStr);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid date format. Use YYYY-MM-DD format.'
            ], 400);
        }

        try {
            // Fetch logs for the specified date, eager loading member details
            $logs = MemberAppOpen::with(['member:member_id,first_name,last_name,mobile,email_address'])
                ->whereDate('created_at', $date)
                ->orderBy('created_at', 'desc')
                ->get();

            // Calculate unique member count
            $uniqueMemberIds = $logs->pluck('member_id')->unique();
            $uniqueCount = $uniqueMemberIds->count();

            return response()->json([
                'success' => true,
                'message' => 'App open logs retrieved successfully',
                'date' => $date->toDateString(),
                'total_opens' => $logs->count(),
                'unique_members_count' => $uniqueCount,
                'data' => $logs
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving app open logs: ' . $e->getMessage()
            ], 500);
        }
    }
}
