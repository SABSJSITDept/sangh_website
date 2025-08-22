<?php

namespace App\Http\Controllers\YuvaSangh\NewsAndEvents;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\YuvaSangh\NewsAndEvents\YuvaNews;
use Illuminate\Support\Facades\Storage;

class AddNewsController extends Controller
{
    public function index()
    {
        return response()->json(YuvaNews::latest()->get());
    }

    public function store(Request $request)
    {
        // Check max 5 news
        if (YuvaNews::count() >= 5) {
            return response()->json(['error' => 'Maximum 5 news allowed.'], 422);
        }

        $data = $request->only(['title', 'description']);

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');

            // Validation for image
            if (!$file->isValid() || !in_array($file->extension(), ['jpg', 'jpeg', 'png', 'webp'])) {
                return response()->json(['error' => 'Invalid image format.'], 422);
            }
            if ($file->getSize() > 200 * 1024) {
                return response()->json(['error' => 'Image must be less than 200KB.'], 422);
            }

            $path = $file->store('yuva_news', 'public');
            $data['photo'] = '/storage/' . $path;
        }

        $news = YuvaNews::create($data);

        return response()->json(['message' => 'News added successfully!', 'news' => $news]);
    }

    public function update(Request $request, $id)
    {
        $news = YuvaNews::findOrFail($id);

        $data = $request->only(['title', 'description']);

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');

            if (!$file->isValid() || !in_array($file->extension(), ['jpg', 'jpeg', 'png', 'webp'])) {
                return response()->json(['error' => 'Invalid image format.'], 422);
            }
            if ($file->getSize() > 200 * 1024) {
                return response()->json(['error' => 'Image must be less than 200KB.'], 422);
            }

            // delete old photo
            if ($news->photo && file_exists(public_path($news->photo))) {
                unlink(public_path($news->photo));
            }

            $path = $file->store('yuva_news', 'public');
            $data['photo'] = '/storage/' . $path;
        }

        $news->update($data);

        return response()->json(['message' => 'News updated successfully!', 'news' => $news]);
    }

    public function destroy($id)
    {
        $news = YuvaNews::findOrFail($id);

        if ($news->photo && file_exists(public_path($news->photo))) {
            unlink(public_path($news->photo));
        }

        $news->delete();

        return response()->json(['message' => 'News deleted successfully!']);
    }
}
