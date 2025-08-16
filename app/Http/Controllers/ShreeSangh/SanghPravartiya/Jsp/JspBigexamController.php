<?php

namespace App\Http\Controllers\ShreeSangh\SanghPravartiya\Jsp;

use App\Http\Controllers\Controller;
use App\Models\ShreeSangh\SanghPravartiya\Jsp\JspBigexam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class JspBigexamController extends Controller
{
 public function index()
{
    $entries = JspBigexam::orderBy('priority', 'asc')->get();
    return response()->json($entries);
}

public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string',
        'pdf' => 'required|mimes:pdf|max:2048',
        'priority' => 'required|integer|min:1',
    ]);

    DB::transaction(function () use ($request, &$entry) {

        // Temporarily make conflicting priorities negative
        JspBigexam::where('priority', '>=', $request->priority)
            ->update(['priority' => DB::raw('priority + 1000')]); // large offset

        $path = $request->file('pdf')->store('jsp_bigexam', 'public');

        $entry = JspBigexam::create([
            'name' => $request->name,
            'pdf' => $path,
            'priority' => $request->priority,
        ]);

        // Restore priorities to correct values
        $all = JspBigexam::orderBy('priority', 'asc')->get();
        $prio = 1;
        foreach ($all as $item) {
            $item->priority = $prio++;
            $item->save();
        }
    });

    return response()->json($entry);
}

public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string',
        'priority' => 'required|integer|min:1',
        'pdf' => 'nullable|mimes:pdf|max:2048',
    ]);

    $entry = JspBigexam::findOrFail($id);

    DB::transaction(function () use ($request, $entry) {

        $entry->name = $request->name;

        if ($request->hasFile('pdf')) {
            Storage::disk('public')->delete($entry->pdf);
            $entry->pdf = $request->file('pdf')->store('jsp_bigexam', 'public');
        }

        // Temporarily move old priority to a safe value
        $entry->priority += 1000;
        $entry->save();

        // Shift all priorities >= new priority
        JspBigexam::where('id', '!=', $entry->id)
            ->where('priority', '>=', $request->priority)
            ->increment('priority');

        // Set the entry's priority
        $entry->priority = $request->priority;
        $entry->save();

        // Reorder all to make sequence continuous
        $all = JspBigexam::orderBy('priority', 'asc')->get();
        $prio = 1;
        foreach ($all as $item) {
            $item->priority = $prio++;
            $item->save();
        }
    });

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
