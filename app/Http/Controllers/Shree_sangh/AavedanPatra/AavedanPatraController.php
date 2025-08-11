<?php

namespace App\Http\Controllers\Shree_sangh\AavedanPatra;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShreeSangh\AavedanPatra\AavedanPatra;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AavedanPatraController extends Controller
{
  public function index(Request $request)
{
    $query = \App\Models\ShreeSangh\AavedanPatra\AavedanPatra::query();

    // ✅ Filter by category if passed in query
    if ($request->has('category')) {
        $query->where('category', $request->category);
    }

    // ✅ Define category priority
    $categoryOrder = [
        'संघ सदस्यता आवेदन-पत्र',
        'अन्य विशिष्ट सदस्यता आवेदन-पत्र',
        'अन्य सदस्यता आवेदन-पत्र',
        'पाठशाला आवेदन-पत्र',
        'शिविर आवेदन-पत्र',
        'स्वाध्यायी पंजीकरण आवेदन-पत्र',
        'गणेश जैन छात्रावास',
        'श्री समता जनकल्याण प्रन्यास',
        'समता छात्रवृत्ति आवेदन-पत्र',
        'पूज्य आचार्य श्री श्रीलाल उच्च शिक्षा योजना आवेदन-पत्र',
        'आचार्य श्री नानेश समता पुरस्कार हेतु प्रविष्टियाँ आमंत्रित',
        'सेठ श्री चम्पालाल सांड स्मृति उच्च प्रशासनिक पुरस्कार',
        'स्व. श्री प्रदीप कुमार रामपुरिया स्मृति साहित्य पुरस्कार प्रतियोगिता आवेदन प्रपत्र',
        'परीक्षा आवेदन-पत्र',
        'अन्य आवेदन-पत्र',
    ];

    // ✅ Apply ordering by category and then by preference
    $query->orderByRaw("
        FIELD(category, '" . implode("','", $categoryOrder) . "')
    ")->orderBy('preference');

    return $query->get();
}



  public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'file_type' => 'required|in:pdf,google_form',
        'category' => 'required|string|max:255',
        'preference' => 'nullable|integer|min:0',
        'file' => $request->file_type === 'pdf'
            ? 'required|mimes:pdf|max:3072'
                        : 'required|string',
    ]);

    if ($request->file_type === 'pdf' && $request->hasFile('file')) {
        $filename = time().'_'.Str::random(10).'.'.$request->file('file')->extension();
        $request->file('file')->storeAs('public/aavedan_patra', $filename);
        $filePath = $filename;
    } else {
        $filePath = $request->file; // google form link
    }

    $data = AavedanPatra::create([
        'name' => $request->name,
        'file' => $filePath,
        'file_type' => $request->file_type,
        'category' => $request->category,
        'preference' => $request->preference ?? 0,
    ]);

    return response()->json($data, 201);
}

public function update(Request $request, $id)
{
    $data = AavedanPatra::findOrFail($id);

    $request->validate([
        'name' => 'required|string|max:255',
        'file_type' => 'required|in:pdf,google_form',
        'category' => 'required|string|max:255',
        'preference' => 'nullable|integer|min:0',
        'file' => $request->file_type === 'pdf'
            ? 'nullable|mimes:pdf|max:3072'
            : 'required|string',
    ]);

    if ($request->file_type === 'pdf' && $request->hasFile('file')) {
        if ($data->file_type === 'pdf' && Storage::exists('public/aavedan_patra/' . $data->file)) {
            Storage::delete('public/aavedan_patra/' . $data->file);
        }

        $filename = time().'_'.Str::random(10).'.'.$request->file('file')->extension();
        $request->file('file')->storeAs('public/aavedan_patra', $filename);
        $filePath = $filename;
    } elseif ($request->file_type === 'google_form') {
        $filePath = $request->file;
    } else {
        $filePath = $data->file;
    }

    $data->update([
        'name' => $request->name,
        'file' => $filePath,
        'file_type' => $request->file_type,
        'category' => $request->category,
        'preference' => $request->preference ?? 0,
    ]);

    return response()->json($data);
}

public function getByCategory($category)
{
    return response()->json(
        AavedanPatra::where('category', $category)
            ->orderBy('preference')
            ->get()
    );
}


    public function destroy($id)
    {
        $data = AavedanPatra::findOrFail($id);

        if ($data->file_type === 'pdf' && Storage::exists('public/aavedan_patra/' . $data->file)) {
            Storage::delete('public/aavedan_patra/' . $data->file);
        }

        $data->delete();

        return response()->json(['message' => 'Deleted']);
    }
    // 🔹 Only Online (Google Form) entries
public function onlyOnline()
{
    return response()->json(
        AavedanPatra::where('file_type', 'google_form')
            ->orderBy('category')
            ->orderBy('preference')
            ->get()
    );
}

public function onlyOffline()
{
    return response()->json(
        AavedanPatra::where('file_type', 'pdf')
            ->orderBy('category')
            ->orderBy('preference')
            ->get()
    );
}


}
