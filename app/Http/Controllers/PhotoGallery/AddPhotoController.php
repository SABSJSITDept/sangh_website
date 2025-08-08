<?php

// app/Http/Controllers/PhotoGallery/AddPhotoController.php
namespace App\Http\Controllers\PhotoGallery;

use App\Http\Controllers\Controller;
use App\Models\PhotoGallery\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AddPhotoController extends Controller
{
public function store(Request $request)
{
    $request->validate([
        'category' => 'required|in:sangh,yuva,mahila',
        'event_name' => 'required|string|max:255',
        'photos' => 'required|array|max:10', // ✅ Max 10 photos allowed
        'photos.*' => 'required|image|max:200', // ✅ 200 KB limit (200 KB = 200 KB, Laravel में KB में होता है)
    ], [
        'photos.max' => 'आप अधिकतम 10 फोटो अपलोड कर सकते हैं।',
        'photos.*.max' => 'प्रत्येक फोटो 200 KB से अधिक नहीं होनी चाहिए।'
    ]);

    if ($request->hasFile('photos')) {
        $photoPaths = [];
        foreach ($request->file('photos') as $photo) {
            $path = $photo->store("public/{$request->category}");
            $photoPaths[] = Storage::url($path);
        }

        Photo::create([
            'category' => $request->category,
            'event_name' => $request->event_name,
            'photos' => json_encode($photoPaths), // ✅ Array store करने के लिए
        ]);

        return response()->json(['message' => 'Photos uploaded successfully']);
    }

    return response()->json(['error' => 'No photos uploaded'], 400);
}


 public function fetchByCategoryEvent($category)
{
    $data = Photo::where('category', $category)
        ->orderBy('created_at', 'desc')
        ->get()
        ->groupBy('event_name')
        ->map(function ($group) {
            return [
                'event_name' => $group->first()->event_name,
                'photos' => collect($group)->flatMap(function ($item) {
                    return collect($item->photos)->map(function ($photo) use ($item) {
                        return [
                            'id' => $item->id,
                            'url' => $photo
                        ];
                    });
                })->values()
            ];
        })
        ->values();

    return response()->json($data);
}


  public function deleteSinglePhoto(Request $request, $id)
{
    $request->validate([
        'photo_url' => 'required|string'
    ]);

    $photo = Photo::findOrFail($id);

    // Delete file from storage
    $relativePath = str_replace('/storage/', 'public/', $request->photo_url);
    Storage::delete($relativePath);

    // Remove from array
    $photosArray = $photo->photos;
    $photosArray = array_values(array_filter($photosArray, fn($p) => $p !== $request->photo_url));

    // If array empty → delete record
    if (empty($photosArray)) {
        $photo->delete();
    } else {
        $photo->photos = $photosArray;
        $photo->save();
    }

    return response()->json(['message' => 'Photo deleted successfully']);
}



    public function updatePhoto(Request $request, $id)
{
    $photo = Photo::findOrFail($id);

    $request->validate([
        'event_name' => 'nullable|string|max:255',
        'new_photo' => 'nullable|image|max:2048',
    ]);

    // Event name change
    if ($request->event_name) {
        $photo->event_name = $request->event_name;
    }

    // Replace specific photo (keeping other ones)
    if ($request->hasFile('new_photo') && $request->old_photo) {
        $oldPhotoPath = str_replace('/storage/', 'public/', $request->old_photo);
        Storage::delete($oldPhotoPath);

        $path = $request->file('new_photo')->store("public/{$photo->category}");
        $newPhotoUrl = Storage::url($path);

        $photosArray = $photo->photos;
        $index = array_search($request->old_photo, $photosArray);
        if ($index !== false) {
            $photosArray[$index] = $newPhotoUrl;
        }
        $photo->photos = $photosArray;
    }

    $photo->save();

    return response()->json(['message' => 'Updated successfully']);
}

// Add this method for updating event name
public function updateEventName(Request $request, $eventName)
{
    $request->validate([
        'event_name' => 'required|string|max:255',
    ]);

    // Update all rows with matching event_name
    Photo::where('event_name', $eventName)->update([
        'event_name' => $request->event_name
    ]);

    return response()->json(['message' => 'Event name updated successfully']);
}




// Add this method for deleting an entire event
public function deleteEvent($eventName, $category)
{
    $photos = Photo::where('category', $category)
        ->where('event_name', $eventName)
        ->get();

    foreach ($photos as $photo) {
        foreach ($photo->photos as $file) {
            $relativePath = str_replace('/storage/', 'public/', $file);
            Storage::delete($relativePath);
        }
        $photo->delete();
    }

    return response()->json(['message' => 'Event deleted successfully']);
}


}
