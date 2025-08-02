<?php

namespace App\Http\Controllers\ShreeSangh\SanghPravartiya\Jsp;

use App\Models\ShreeSangh\SanghPravartiya\Jsp\JspOldPapers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class JspOldPapersController extends Controller
{
   public function index()
{
    $papers = JspOldPapers::orderBy('class')
        ->orderByDesc('year')
        ->get()
        ->groupBy('class');

    return response()->json($papers);
}


    public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'class' => 'required|integer|between:1,12',
        'year' => 'required|integer|min:2000|max:' . date('Y'),
        'pdf' => 'required|mimes:pdf|max:2048'
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    // 🔐 Custom validation: only one paper per class/year
    $exists = JspOldPapers::where('class', $request->class)
                          ->where('year', $request->year)
                          ->exists();

    if ($exists) {
        return response()->json([
            'errors' => [
                'duplicate' => ['Paper already exists for this Class and Year.']
            ]
        ], 422);
    }

    $pdfPath = $request->file('pdf')->store('old_papers', 'public');

    $paper = JspOldPapers::create([
        'class' => $request->class,
        'year' => $request->year,
        'pdf' => $pdfPath,
    ]);

    return response()->json(['message' => 'Old Paper Added Successfully', 'data' => $paper]);
}


    public function destroy($id)
    {
        $paper = JspOldPapers::findOrFail($id);
        Storage::disk('public')->delete($paper->pdf);
        $paper->delete();

        return response()->json(['message' => 'Old Paper Deleted Successfully']);
    }
}
