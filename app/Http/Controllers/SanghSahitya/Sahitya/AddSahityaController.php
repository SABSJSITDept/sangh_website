<?php

namespace App\Http\Controllers\SanghSahitya\Sahitya;

use App\Http\Controllers\Controller;
use App\Models\SanghSahitya\Sahitya;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AddSahityaController extends Controller
{
    public function index()
    {
        return Sahitya::orderBy('preference')->get();
    }

public function store(Request $request)
{
    $request->validate([
        'category' => 'required|string',
        'name' => 'required|string|max:255',
        'cover_photo' => 'required|image|max:200',
        'pdf' => 'nullable|mimes:pdf|max:2048',
        'preference' => 'required|integer',
        'show_on_homepage' => 'required|boolean',
    ]);

    // homepage check
    if ($request->show_on_homepage == 1 && Sahitya::where('show_on_homepage', 1)->exists()) {
        return response()->json(['success' => false, 'message' => 'Only one homepage book allowed.'], 422);
    }

    // Shift others' preferences only within same category
    $this->shiftPreferences($request->category, $request->preference);

    $coverPath = $request->file('cover_photo')->store('public/sahitya/covers');
    $pdfPath = $request->hasFile('pdf')
        ? $request->file('pdf')->store("public/sahitya/{$request->category}")
        : null;

    $sahitya = Sahitya::create([
        'category' => $request->category,
        'name' => $request->name,
        'cover_photo' => Storage::url($coverPath),
        'pdf' => $pdfPath ? Storage::url($pdfPath) : null,
        'preference' => $request->preference,
        'show_on_homepage' => $request->show_on_homepage,
    ]);

    return response()->json(['success' => true, 'data' => $sahitya], 201);
}



  public function update(Request $request, $id)
{
    $sahitya = Sahitya::findOrFail($id);

    $request->validate([
        'category' => 'required|string',
        'name' => 'required|string|max:255',
        'cover_photo' => 'nullable|image|max:200',
        'pdf' => 'nullable|mimes:pdf|max:2048',
        'preference' => 'required|integer',
        'show_on_homepage' => 'required|boolean',
    ]);

    if ($request->show_on_homepage == 1) {
        Sahitya::where('show_on_homepage', 1)
            ->where('id', '!=', $sahitya->id)
            ->update(['show_on_homepage' => 0]);
    }

    // Shift preferences if changed
    if ($request->preference != $sahitya->preference || $request->category != $sahitya->category) {
        $this->shiftPreferences($request->category, $request->preference, $sahitya->id);
    }

    // Handle files
    if ($request->hasFile('cover_photo')) {
        Storage::delete(str_replace('/storage/', 'public/', $sahitya->cover_photo));
        $sahitya->cover_photo = Storage::url($request->file('cover_photo')->store('public/sahitya/covers'));
    }

    if ($request->hasFile('pdf')) {
        if ($sahitya->pdf) {
            Storage::delete(str_replace('/storage/', 'public/', $sahitya->pdf));
        }
        $sahitya->pdf = Storage::url($request->file('pdf')->store("public/sahitya/{$request->category}"));
    }

    // Update other fields
    $sahitya->update([
        'category' => $request->category,
        'name' => $request->name,
        'preference' => $request->preference,
        'show_on_homepage' => $request->show_on_homepage,
    ]);

    return response()->json(['success' => true]);
}

private function shiftPreferences($category, $newPref, $exceptId = null)
{
    $booksToShift = Sahitya::where('category', $category)
        ->where('preference', '>=', $newPref)
        ->when($exceptId, fn($q) => $q->where('id', '!=', $exceptId))
        ->orderBy('preference', 'desc')
        ->get();

    foreach ($booksToShift as $book) {
        $book->preference = $book->preference + 1;
        $book->save();
    }
}



    public function destroy(Sahitya $sahitya)
    {
        if ($sahitya->cover_photo) {
            Storage::delete(str_replace('/storage/', 'public/', $sahitya->cover_photo));
        }
        if ($sahitya->pdf) {
            Storage::delete(str_replace('/storage/', 'public/', $sahitya->pdf));
        }
        $sahitya->delete();

        return response()->json(['success' => true]);
    }

    // 🔁 Category-wise fetch
    public function getByCategory($category)
    {
        return Sahitya::where('category', $category)
                      ->orderBy('preference')
                      ->get();
    }

    // 🏠 Homepage books fetch
    public function getHomepageBooks()
    {
        try {
            $books = Sahitya::select('id', 'category', 'name', 'cover_photo', 'pdf', 'preference', 'show_on_homepage')
                ->where('show_on_homepage', 1)
                ->orderBy('preference')
                ->get();

            return response()->json($books, 200);
        } catch (\Exception $e) {
            Log::error('Error in getHomepageBooks: ' . $e->getMessage());
            return response()->json(['error' => 'Server Error'], 500);
        }
    }

    // 🟢 Set Homepage Book via Button
    public function setHomepageBook($id)
    {
        try {
            // Reset all books to not show on homepage
            Sahitya::where('show_on_homepage', 1)->update(['show_on_homepage' => 0]);

            // Set selected one
            $book = Sahitya::findOrFail($id);
            $book->show_on_homepage = 1;
            $book->save();

            return response()->json(['success' => true, 'message' => 'Homepage book updated successfully.']);
        } catch (\Exception $e) {
            Log::error('Error in setHomepageBook: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to set homepage book.'], 500);
        }
    }
}
