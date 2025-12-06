<?php

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
            'category' => 'required|in:sangh,yuva,mahila,spf',
            'event_name' => 'required|string|max:255',
            'photos' => 'required|array|max:10', // Max 10 photos allowed
            'photos.*' => 'required|image|max:200', // 200 KB max per photo
            'drive_link' => 'nullable|url|max:500', // Optional drive link
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
                'photos' => $photoPaths,  // Store as array directly, model will cast
                'drive_link' => $request->drive_link, // Store optional drive link
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
                    'drive_link' => $group->first()->drive_link,
                    'photos' => collect($group)->flatMap(function ($item) {
                        return collect($item->photos)->map(function ($photo) use ($item) {
                            // Convert URL properly and fix escaped slashes
                            $relativePath = str_replace('/storage/', 'public/', $photo);
                            $url = Storage::exists($relativePath) ? asset(Storage::url($relativePath)) : asset($photo);
                            $url = str_replace('\\/', '/', $url); // fix escaped slashes

                            return [
                                'id' => $item->id,
                                'url' => $url,
                            ];
                        });
                    })->values(),
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

    // Convert full URL to relative
    $relativeUrl = str_replace(url('/'), '', $request->photo_url); // "/storage/sangh/abc.jpg"

    // Delete file
    $relativePath = str_replace('/storage/', 'public/', $relativeUrl);
    Storage::delete($relativePath);

    // Remove from array
    $photosArray = $photo->photos;
    $photosArray = array_values(array_filter($photosArray, fn($p) => $p !== $relativeUrl));

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
        'old_photo' => 'nullable|string',
        'drive_link' => 'nullable|url|max:500'
    ]);

    // Update event name if provided
    if ($request->event_name) {
        $photo->event_name = $request->event_name;
    }

    // Update drive link if provided
    if ($request->has('drive_link')) {
        $photo->drive_link = $request->drive_link;
    }

    // Replace specific photo if new photo uploaded
    if ($request->hasFile('new_photo') && $request->old_photo) {

        // Convert old photo full URL to relative path
        $oldRelativeUrl = str_replace(url('/'), '', $request->old_photo); // "/storage/..."

        // Delete old file from storage
        $oldRelativePath = str_replace('/storage/', 'public/', $oldRelativeUrl);
        Storage::delete($oldRelativePath);

        // Store new photo
        $path = $request->file('new_photo')->store("public/{$photo->category}");
        $newPhotoUrl = Storage::url($path); // "/storage/..."

        // Replace in array
        $photosArray = $photo->photos;
        $index = array_search($oldRelativeUrl, $photosArray);
        if ($index !== false) {
            $photosArray[$index] = $newPhotoUrl;
        }

        $photo->photos = $photosArray;
    }

    $photo->save();

    return response()->json(['message' => 'Updated successfully']);
}



    public function updateEventName(Request $request, $eventName)
    {
        $request->validate([
            'event_name' => 'required|string|max:255',
        ]);

        Photo::where('event_name', $eventName)->update([
            'event_name' => $request->event_name
        ]);

        return response()->json(['message' => 'Event name updated successfully']);
    }

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
