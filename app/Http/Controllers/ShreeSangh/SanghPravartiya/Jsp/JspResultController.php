<?php

namespace App\Http\Controllers\ShreeSangh\SanghPravartiya\Jsp;

use App\Models\ShreeSangh\SanghPravartiya\Jsp\JspResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use PhpOffice\PhpSpreadsheet\IOFactory;

class JspResultController extends Controller
{
    private function getValidationRules()
    {
        return [
            'Student_Name' => 'nullable|string',
            'Guardian_Name' => 'nullable|string',
            'Mobile' => 'nullable|string',
            'City' => 'nullable|string',
            'State' => 'nullable|string',
            'Class' => 'required|string',
            'Marks' => 'nullable|string',
            'Rank' => 'nullable|string',
            'Remarks' => 'nullable|string'
        ];
    }

    // Display all results
    public function index()
    {
        return response()->json(JspResult::all());
    }

    // Store single or bulk results
    public function store(Request $request)
    {
        if ($request->has('bulk') && is_array($request->input('bulk'))) {
            // Check if we should clear existing results of a class first
            if ($request->has('clear_class') && !empty($request->input('clear_class'))) {
                JspResult::where('Class', trim($request->input('clear_class')))->delete();
            }

            // Bulk upload from JSON
            $bulkData = $request->input('bulk');
            $inserted = [];
            $errors = [];
            
            foreach ($bulkData as $idx => $row) {
                if (empty(array_filter($row))) continue; // skip empty rows
                
                // Trim string values in $row to avoid spaces around values
                $row = array_map(function($val) {
                    return is_string($val) ? trim($val) : $val;
                }, $row);
                
                $validator = Validator::make($row, $this->getValidationRules());
                if ($validator->fails()) {
                    $errors[] = ['row' => $idx + 1, 'errors' => $validator->errors()->all()];
                    continue;
                }
                $inserted[] = JspResult::create($row);
            }
            
            if (!empty($errors)) {
                return response()->json([
                    'success' => false,
                    'inserted' => count($inserted),
                    'errors' => $errors,
                    'message' => count($inserted) . ' records inserted, ' . count($errors) . ' records failed validation'
                ], 422);
            }
            
            return response()->json(['success' => true, 'inserted' => count($inserted), 'message' => count($inserted) . ' records inserted']);
        } else {
            // Single record
            $payload = array_map(function($val) {
                return is_string($val) ? trim($val) : $val;
            }, $request->all());

            $validator = Validator::make($payload, $this->getValidationRules());
            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            $result = JspResult::create($payload);
            return response()->json(['success' => true, 'data' => $result]);
        }
    }

    // Show single result
    public function show($id)
    {
        return response()->json(JspResult::findOrFail($id));
    }

    // Update result
    public function update(Request $request, $id)
    {
        $result = JspResult::findOrFail($id);
        $result->update($request->all());
        return response()->json(['success' => true, 'data' => $result]);
    }

    // Delete result
    public function destroy($id)
    {
        JspResult::destroy($id);
        return response()->json(['success' => true]);
    }

    public function getResult(Request $request)
    {
        $status = \App\Models\Status\Status::where('name', 'JSP_RESULT')->first();
        $visible = $status ? (bool)$status->status : true;
        if (!$visible) {
            return response()->json(['message' => 'Results are currently hidden or not declared yet.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'class' => 'required|string',
            'mobile' => 'required|regex:/^\d{10}$/',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $class = trim($request->input('class'));
        $mobile = trim($request->input('mobile'));

        $results = DB::table('jsp_result')
            ->where('Class', $class)
            ->where('Mobile', $mobile)
            ->get();

        if ($results->isEmpty()) {
            return response()->json(['message' => 'Result not found'], 404);
        }

        return response()->json(['result' => $results]);
    }

    public function filterData(Request $request)
    {
        $query = JspResult::query();

        if ($request->has('class_name') && !empty($request->class_name)) {
            $query->where('Class', 'LIKE', '%' . $request->class_name . '%');
        }

        if ($request->has('phone_number') && !empty($request->phone_number)) {
            $query->where('Mobile', 'LIKE', '%' . $request->phone_number . '%');
        }

        if ($request->has('city') && !empty($request->city)) {
            $query->where('City', 'LIKE', '%' . $request->city . '%');
        }

        if ($request->has('state') && !empty($request->state)) {
            $query->where('State', 'LIKE', '%' . $request->state . '%');
        }

        $results = $query->get();

        return response()->json($results);
    }

    public function getVisibility()
    {
        $status = \App\Models\Status\Status::where('name', 'JSP_RESULT')->first();
        $visible = $status ? (bool)$status->status : true;
        return response()->json(['visible' => $visible]);
    }

    public function toggleVisibility(Request $request)
    {
        $request->validate([
            'visible' => 'required|boolean'
        ]);

        $visible = $request->input('visible');

        $status = \App\Models\Status\Status::updateOrCreate(
            ['name' => 'JSP_RESULT'],
            ['status' => $visible ? 1 : 0]
        );

        return response()->json(['success' => true, 'visible' => (bool)$status->status]);
    }
}