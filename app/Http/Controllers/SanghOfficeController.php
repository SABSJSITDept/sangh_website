<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\SanghOffice;

class SanghOfficeController extends Controller
{
    public function index()
    {
        return response()->json(SanghOffice::orderBy('sequence', 'asc')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'google_link' => 'nullable|string|max:255',
            'phone_numbers' => 'nullable|array',
            'emails' => 'nullable|array',
            'sequence' => 'integer',
        ]);

        if (isset($validated['sequence'])) {
            SanghOffice::where('sequence', '>=', $validated['sequence'])->increment('sequence');
        }

        $office = SanghOffice::create($validated);
        return response()->json($office, 201);
    }

    public function update(Request $request, $id)
    {
        $office = SanghOffice::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'address' => 'nullable|string',
            'google_link' => 'nullable|string|max:255',
            'phone_numbers' => 'nullable|array',
            'emails' => 'nullable|array',
            'sequence' => 'integer',
        ]);

        if (isset($validated['sequence']) && $office->sequence != $validated['sequence']) {
            SanghOffice::where('sequence', '>=', $validated['sequence'])
                ->where('id', '!=', $office->id)
                ->increment('sequence');
        }

        $office->update($validated);
        return response()->json($office);
    }

    public function destroy($id)
    {
        $office = SanghOffice::findOrFail($id);
        $office->delete();

        return response()->json(['success' => true]);
    }
}
