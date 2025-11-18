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
            'Student_Name' => 'nullable|string|regex:/^[a-zA-Z\s]+$/',
            'Guardian_Name' => 'nullable|string|regex:/^[a-zA-Z\s]+$/',
            'Mobile' => 'nullable|regex:/^\d{10}$/',
            'City' => 'nullable|string|regex:/^[a-zA-Z\s]+$/',
            'State' => 'nullable|string|regex:/^[a-zA-Z\s]+$/',
            'Class' => 'required|string',
            'Marks' => 'nullable|integer|min:0|max:100',
            'Rank' => 'nullable|string|regex:/^[a-zA-Z0-9\s]+$/',
            'Remarks' => 'nullable|string|regex:/^[a-zA-Z0-9\s.,\-()]+$/'
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
            // Bulk upload from JSON
            $bulkData = $request->input('bulk');
            $inserted = [];
            $errors = [];
            
            foreach ($bulkData as $idx => $row) {
                if (empty(array_filter($row))) continue; // skip empty rows
                
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
            $validator = Validator::make($request->all(), $this->getValidationRules());
            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            $result = JspResult::create($request->all());
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
        $validator = Validator::make($request->all(), [
            'class' => 'required|string',
            'mobile' => 'required|regex:/^\d{10}$/',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $class = $request->input('class');
        $mobile = $request->input('mobile');

        $result = DB::table('jsp_result')
            ->where('Class', $class)
            ->where('Mobile', $mobile)
            ->first();

        if (!$result) {
            return response()->json(['message' => 'Result not found'], 404);
        }

        return response()->json(['result' => $result]);
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
}