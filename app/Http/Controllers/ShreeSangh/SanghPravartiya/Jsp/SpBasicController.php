<?php

namespace App\Http\Controllers\ShreeSangh\SanghPravartiya\Jsp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShreeSangh\SanghPravartiya\Jsp\JspBasic;

class SpBasicController extends Controller
{
    public function index()
    {
        return JspBasic::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'dtp' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $path = $request->file('dtp')->store('jsp_images', 'public');

        return JspBasic::create([
            'content' => $request->content,
            'dtp' => $path
        ]);
    }

    public function show($id)
    {
        $record = JspBasic::find($id);

        if (!$record) {
            return response()->json(['message' => 'Record not found.'], 404);
        }

        return response()->json($record);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string',
            'dtp' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $record = JspBasic::findOrFail($id);

        $data = ['content' => $request->content];

        if ($request->hasFile('dtp')) {
            $data['dtp'] = $request->file('dtp')->store('jsp_images', 'public');
        }

        $record->update($data);

        return $record;
    }

    public function destroy($id)
    {
        $jsp = JspBasic::findOrFail($id);
        $jsp->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }
}
