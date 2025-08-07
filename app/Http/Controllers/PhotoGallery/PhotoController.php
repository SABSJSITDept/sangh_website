<?php

// app/Http/Controllers/PhotoGallery/PhotoController.php

namespace App\Http\Controllers\PhotoGallery;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PhotoGallery\Photo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PhotoController extends Controller
{
    public function index() {
        return view('dashboard.photo_gallery.add_photo');
    }

   public function store(Request $request)
{
    try {
        $request->validate([
            'category' => 'required|in:sangh,yuva,mahila',
            'event_name' => 'required|string|max:255',
            'photos.*' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if (!$request->hasFile('photos')) {
            return response()->json(['message' => 'No photo files received.'], 400);
        }

        foreach ($request->file('photos') as $photo) {
            $filename = uniqid() . '.' . $photo->getClientOriginalExtension();
            $photo->storeAs("public/{$request->category}", $filename);

            Photo::create([
                'category' => $request->category,
                'event_name' => $request->event_name,
                'photo' => "{$request->category}/$filename",
            ]);
        }

        return response()->json(['message' => 'Photos uploaded successfully!']);
    } catch (\Exception $e) {
        return response()->json(['message' => $e->getMessage()], 500);
    }
}


    public function destroy($id) {
        $photo = Photo::findOrFail($id);

        if (Storage::exists("public/{$photo->photo}")) {
            Storage::delete("public/{$photo->photo}");
        }

        $photo->delete();
        return response()->json(['success' => true, 'message' => 'Photo deleted successfully!']);
    }

    // Fetch API category-wise
    public function fetchSangh() {
        return response()->json(Photo::where('category', 'sangh')->get()->groupBy('event_name'));
    }

    public function fetchYuva() {
        return response()->json(Photo::where('category', 'yuva')->get()->groupBy('event_name'));
    }

    public function fetchMahila() {
        return response()->json(Photo::where('category', 'mahila')->get()->groupBy('event_name'));
    }
}
