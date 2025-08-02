<?php

namespace App\Http\Controllers\ShreeSangh\SanghPravartiya\Jsp;

use App\Http\Controllers\Controller;
use App\Models\ShreeSangh\SanghPravartiya\Jsp\JspBigexam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class JspBigexamController extends Controller
{
    public function index()
    {
        return response()->json(JspBigexam::all());
    }

public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string',
        'pdf' => 'required|mimes:pdf|max:2048',
        'priority' => 'required|integer|unique:jsp_bigexam,priority'
    ]);

    $path = $request->file('pdf')->store('jsp_bigexam', 'public');

    $entry = JspBigexam::create([
        'name' => $request->name,
        'pdf' => $path,
        'priority' => $request->priority,
    ]);

    return response()->json($entry);
}

public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string',
        'priority' => 'required|integer|unique:jsp_bigexam,priority,' . $id,
        'pdf' => 'nullable|mimes:pdf|max:2048'
    ]);

    $entry = JspBigexam::findOrFail($id);
    $entry->name = $request->name;
    $entry->priority = $request->priority;

    if ($request->hasFile('pdf')) {
        Storage::disk('public')->delete($entry->pdf);
        $entry->pdf = $request->file('pdf')->store('jsp_bigexam', 'public');
    }

    $entry->save();
    return response()->json($entry);
}

    public function destroy($id)
    {
        $entry = JspBigexam::findOrFail($id);
        Storage::disk('public')->delete($entry->pdf);
        $entry->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
