<?php

namespace App\Http\Controllers\ShreeSangh\SanghPravartiya\Jsp;

use App\Models\ShreeSangh\SanghPravartiya\Jsp\JspExam;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;


class JspExamController extends Controller
{
    public function index()
    {
        return response()->json(JspExam::all());
    }


public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string',
        'pdf' => 'nullable|file|mimes:pdf',
        'google_form_link' => 'nullable|string',
    ]);

    $path = null;
    if ($request->hasFile('pdf')) {
        $path = $request->file('pdf')->store('jsp_exam', 'public');
    }

    $exam = JspExam::create([
        'name' => $request->name,
        'pdf' => $path,
        'google_form_link' => $request->google_form_link,
    ]);

    return response()->json($exam);
}

public function update(Request $request, $id)
{
    $exam = JspExam::findOrFail($id);

    $request->validate([
        'name' => 'required|string',
        'pdf' => 'nullable|file|mimes:pdf',
        'google_form_link' => 'nullable|string',
    ]);

    $data = [
        'name' => $request->name,
        'google_form_link' => $request->google_form_link,
    ];

    if ($request->hasFile('pdf')) {
        if ($exam->pdf && Storage::disk('public')->exists($exam->pdf)) {
            Storage::disk('public')->delete($exam->pdf);
        }
        $data['pdf'] = $request->file('pdf')->store('jsp_exam', 'public');
    }

    $exam->update($data);
    return response()->json($exam);
}   
    public function destroy($id)
    {
        JspExam::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
