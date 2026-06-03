<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\SanghHelpline;

class SanghHelplineController extends Controller
{
    public function index()
    {
        return response()->json(SanghHelpline::orderBy('sequence', 'asc')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'dept_name' => 'required|string|max:255',
            'mobile_number' => 'nullable|array',
            'email' => 'nullable|array',
            'whatsapp_number' => 'nullable|string|max:255',
            'is_only_whatsapp' => 'boolean',
            'sequence' => 'integer',
        ]);

        $helpline = SanghHelpline::create($validated);
        return response()->json($helpline, 201);
    }

    public function update(Request $request, $id)
    {
        $helpline = SanghHelpline::findOrFail($id);

        $validated = $request->validate([
            'dept_name' => 'sometimes|required|string|max:255',
            'mobile_number' => 'nullable|array',
            'email' => 'nullable|array',
            'whatsapp_number' => 'nullable|string|max:255',
            'is_only_whatsapp' => 'boolean',
            'sequence' => 'integer',
        ]);

        $helpline->update($validated);
        return response()->json($helpline);
    }

    public function destroy($id)
    {
        $helpline = SanghHelpline::findOrFail($id);
        $helpline->delete();

        return response()->json(['success' => true]);
    }
}
