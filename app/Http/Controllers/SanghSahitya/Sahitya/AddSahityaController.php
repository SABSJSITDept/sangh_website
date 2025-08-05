<?php

namespace App\Http\Controllers\SanghSahitya\sahitya;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SanghSahitya\Sahitya;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AddSahityaController extends Controller
{
   public function index()
{
    $orderedCategories = [
        'नानेशवाणी साहित्य',
        'राम उवाच साहित्य',
        'श्री राम ध्वनि',
        'राम दर्शन',
        'समता कथा माला',
        'अन्य प्रकाशित साहित्य',
    ];

    $allData = Sahitya::orderBy('preference', 'asc')->get();

    // Group and sort by fixed category order
    $grouped = collect($orderedCategories)->mapWithKeys(function ($category) use ($allData) {
        return [$category => $allData->where('category', $category)->values()];
    });

    return response()->json($grouped);
}



    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required',
            'name' => 'required|string|max:255',
            'cover_photo' => 'required|image|max:200', // in KB
            'pdf' => 'nullable|mimes:pdf|max:2048', // 2MB
        ]);

        $path = 'sahitya/' . Str::slug($request->category);
        $coverPhotoPath = $request->file('cover_photo')->store($path, 'public');

        $pdfPath = null;
        if ($request->hasFile('pdf')) {
            $pdfPath = $request->file('pdf')->store($path, 'public');
        }

        Sahitya::create([
            'category' => $request->category,
            'name' => $request->name,
            'cover_photo' => $coverPhotoPath,
            'pdf' => $pdfPath,
            'preference' => $request->preference ?? 0,
        ]);

        return response()->json(['message' => 'Sahitya Added Successfully']);
    }

    public function update(Request $request, $id)
{
    $sahitya = Sahitya::findOrFail($id);

    $request->validate([
        'category' => 'required',
        'name' => 'required|string|max:255',
        'cover_photo' => 'nullable|image|max:200', // optional during edit
        'pdf' => 'nullable|mimes:pdf|max:2048',
        'preference' => 'nullable|numeric',
    ]);

    $sahitya->category = $request->category;
    $sahitya->name = $request->name;
    $sahitya->preference = $request->preference ?? 0;

    $path = 'sahitya/' . Str::slug($request->category);

    if ($request->hasFile('cover_photo')) {
        Storage::disk('public')->delete($sahitya->cover_photo);
        $sahitya->cover_photo = $request->file('cover_photo')->store($path, 'public');
    }

    if ($request->hasFile('pdf')) {
        Storage::disk('public')->delete($sahitya->pdf);
        $sahitya->pdf = $request->file('pdf')->store($path, 'public');
    }

    $sahitya->save();

    return response()->json(['message' => 'Sahitya Updated Successfully']);
}

public function show($id)
{
    return response()->json(Sahitya::findOrFail($id));
}


public function destroy($id)
{
    $sahitya = Sahitya::findOrFail($id);

    // Safely delete files only if not null
    $filesToDelete = array_filter([
        $sahitya->cover_photo,
        $sahitya->pdf
    ]);

    Storage::disk('public')->delete($filesToDelete);

    $sahitya->delete();

    return response()->json(['message' => 'Deleted successfully']);
}
public function toggleHomepage($id)
{
    // Reset all to false first
    Sahitya::where('show_on_homepage', true)->update(['show_on_homepage' => false]);

    // Set selected to true
    $sahitya = Sahitya::findOrFail($id);
    $sahitya->show_on_homepage = true;
    $sahitya->save();

    return response()->json(['message' => 'Homepage book updated successfully']);
}

public function homepageSahitya()
{
    $homepageItem = Sahitya::where('show_on_homepage', true)->first();

    if ($homepageItem) {
        return response()->json($homepageItem);
    } else {
        return response()->json(null);
    }
}




}
