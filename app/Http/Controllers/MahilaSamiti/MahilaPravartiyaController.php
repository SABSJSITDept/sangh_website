<?php

namespace App\Http\Controllers\MahilaSamiti;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MahilaSamiti\MahilaPravartiya;
use Illuminate\Support\Facades\Storage;

class MahilaPravartiyaController extends Controller
{
    // ✅ सभी records लाना
    public function index()
    {
        return response()->json(MahilaPravartiya::latest()->get());
    }

    // ✅ नया record जोड़ना
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'required|string',
            'logo'        => 'nullable|image|max:300',
        ], [
            'name.required'        => 'कृपया नाम दर्ज करें।',
            'description.required' => 'कृपया विवरण दर्ज करें।',
            'logo.image'           => 'केवल image file upload करें।',
            'logo.max'             => 'Logo 300KB से बड़ा नहीं होना चाहिए।',
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $path     = $request->file('logo')->store('mahila_pravartiya', 'public');
            $logoPath = '/storage/' . $path;
        }

        $record = MahilaPravartiya::create([
            'name'        => $request->name,
            'description' => $request->description,
            'logo'        => $logoPath,
        ]);

        return response()->json(['success' => true, 'data' => $record]);
    }

    // ✅ एक record
    public function show($id)
    {
        return response()->json(MahilaPravartiya::findOrFail($id));
    }

    // ✅ Update करना
    public function update(Request $request, $id)
    {
        $record = MahilaPravartiya::findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'required|string',
            'logo'        => 'nullable|image|max:300',
        ], [
            'name.required'        => 'कृपया नाम दर्ज करें।',
            'description.required' => 'कृपया विवरण दर्ज करें।',
            'logo.image'           => 'केवल image file upload करें।',
            'logo.max'             => 'Logo 300KB से बड़ा नहीं होना चाहिए।',
        ]);

        $data = [
            'name'        => $request->name,
            'description' => $request->description,
        ];

        if ($request->hasFile('logo')) {
            // पुराना logo delete करें
            if ($record->logo) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $record->logo));
            }
            $path        = $request->file('logo')->store('mahila_pravartiya', 'public');
            $data['logo'] = '/storage/' . $path;
        }

        $record->update($data);

        return response()->json(['success' => true, 'data' => $record]);
    }

    // ✅ Delete करना
    public function destroy($id)
    {
        $record = MahilaPravartiya::findOrFail($id);

        if ($record->logo) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $record->logo));
        }

        $record->delete();

        return response()->json(['success' => true]);
    }
}
