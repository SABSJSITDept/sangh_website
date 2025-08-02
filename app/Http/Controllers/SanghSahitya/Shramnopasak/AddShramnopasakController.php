<?php

namespace App\Http\Controllers\SanghSahitya\Shramnopasak;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SanghSahitya\Shramnopasak;
use Illuminate\Support\Facades\Storage;

class AddShramnopasakController extends Controller
{
    public function index()
    {
        return Shramnopasak::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'year' => 'required|integer',
            'month' => 'required|string',
            'cover_photo' => 'nullable|image|max:200',
            'pdf' => 'nullable|mimes:pdf|max:2048',
        ]);

        $coverPhotoPath = null;
        if ($request->hasFile('cover_photo')) {
            $coverPhotoPath = $request->file('cover_photo')->store('shramnopasak/cover_photos', 'public');
        }

        $pdfPath = null;
        if ($request->hasFile('pdf')) {
            $pdfPath = $request->file('pdf')->store("shramnopasak/{$request->year}", 'public');
        }

        $data = Shramnopasak::create([
            'year' => $request->year,
            'month' => $request->month,
            'cover_photo' => $coverPhotoPath,
            'pdf' => $pdfPath,
        ]);

        return response()->json(['status' => 'success', 'data' => $data]);
    }

    public function update(Request $request, $id)
    {
        $shram = Shramnopasak::findOrFail($id);

        $request->validate([
            'year' => 'required|integer',
            'month' => 'required|string',
            'cover_photo' => 'nullable|image|max:200',
            'pdf' => 'nullable|mimes:pdf|max:2048',
        ]);

        // Delete old files if new ones are uploaded
        if ($request->hasFile('cover_photo')) {
            if ($shram->cover_photo) Storage::disk('public')->delete($shram->cover_photo);
            $shram->cover_photo = $request->file('cover_photo')->store('shramnopasak/cover_photos', 'public');
        }

        if ($request->hasFile('pdf')) {
            if ($shram->pdf) Storage::disk('public')->delete($shram->pdf);
            $shram->pdf = $request->file('pdf')->store("shramnopasak/{$request->year}", 'public');
        }

        $shram->year = $request->year;
        $shram->month = $request->month;
        $shram->save();

        return response()->json(['status' => 'success', 'data' => $shram]);
    }

    public function destroy($id)
    {
        $record = Shramnopasak::findOrFail($id);
        if ($record->cover_photo) Storage::disk('public')->delete($record->cover_photo);
        if ($record->pdf) Storage::disk('public')->delete($record->pdf);
        $record->delete();
        return response()->json(['status' => 'deleted']);
    }

    public function latest()
{
    $currentYear = now()->year;
    $currentMonthNumber = now()->month;

    // Month names array to map number to name
    $months = [
        1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
        5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
        9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
    ];

    for ($m = $currentMonthNumber; $m >= 1; $m--) {
        $entry = Shramnopasak::where('year', $currentYear)
                    ->where('month', $months[$m])
                    ->orderByDesc('id')
                    ->first();
        if ($entry) {
            return response()->json(['status' => 'success', 'data' => $entry]);
        }
    }

    // If no entries in current year, look into previous year (optional)
    for ($prevYear = $currentYear - 1; $prevYear >= 2000; $prevYear--) {
        for ($m = 12; $m >= 1; $m--) {
            $entry = Shramnopasak::where('year', $prevYear)
                        ->where('month', $months[$m])
                        ->orderByDesc('id')
                        ->first();
            if ($entry) {
                return response()->json(['status' => 'success', 'data' => $entry]);
            }
        }
    }

    return response()->json(['status' => 'not_found', 'message' => 'No data found']);
}

}
