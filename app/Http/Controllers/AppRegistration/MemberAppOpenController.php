<?php

namespace App\Http\Controllers\AppRegistration;

use App\Http\Controllers\Controller;
use App\Models\AppRegistration\MemberAppOpen;
use App\Models\AppRegistration\AppRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
            // Sync member details from official API if not present in the local database
            $this->syncMemberDetails($request->member_id);

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

            // Find any logs where the member details are missing locally
            $missingMemberIds = [];
            foreach ($logs as $log) {
                if (!$log->member) {
                    $missingMemberIds[] = $log->member_id;
                }
            }

            if (!empty($missingMemberIds)) {
                $uniqueMissingIds = array_unique($missingMemberIds);
                foreach ($uniqueMissingIds as $id) {
                    $this->syncMemberDetails($id);
                }
                
                // Reload relation for logs
                $logs->load(['member:member_id,first_name,last_name,mobile,email_address']);
            }

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

    /**
     * Sync member details from the official API if not present in the local database.
     */
    private function syncMemberDetails($memberId)
    {
        try {
            // Check if member already exists in local database
            $exists = DB::table('mrm_app')
                ->where('member_id', $memberId)
                ->exists();

            if ($exists) {
                return;
            }

            // Fetch member details from official API
            $response = Http::withHeaders([
                'Accept' => 'application/json'
            ])->timeout(5)->get("https://mrm.sadhumargi.org/api/member/{$memberId}");

            if ($response->successful()) {
                $memberData = $response->json();
                
                // Inspect response structure: could be wrapped in 'data' key or direct
                $data = isset($memberData['data']) ? $memberData['data'] : $memberData;

                if (!empty($data) && (isset($data['first_name']) || isset($data['name']))) {
                    // Extract fields
                    $firstName = $data['first_name'] ?? $data['name'] ?? 'Member';
                    $lastName = $data['last_name'] ?? '';
                    $mobile = $data['mobile'] ?? $data['mobile_number'] ?? $data['phone'] ?? null;
                    $email = $data['email_address'] ?? $data['email'] ?? null;
                    $familyId = $data['family_id'] ?? null;
                    $gender = $data['gender'] ?? null;
                    
                    $birthDay = null;
                    if (isset($data['birth_day']) && !empty($data['birth_day'])) {
                        try {
                            $birthDay = Carbon::parse($data['birth_day'])->toDateString();
                        } catch (\Exception $e) {
                            // ignore date parse error
                        }
                    }

                    // Insert using DB builder to bypass Eloquent events that override member_id/family_id
                    DB::table('mrm_app')->insert([
                        'member_id' => $memberId,
                        'family_id' => $familyId,
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'mobile' => $mobile,
                        'email_address' => $email,
                        'gender' => $gender,
                        'birth_day' => $birthDay,
                        'app_status' => 1,
                        'registration' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::warning("Failed to sync member_id {$memberId}: " . $e->getMessage());
        }
    }
}
