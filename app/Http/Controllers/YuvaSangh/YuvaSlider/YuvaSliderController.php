<?php

namespace App\Http\Controllers\YuvaSangh\YuvaSlider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\YuvaSangh\YuvaSlider\YuvaSlider;
use Illuminate\Support\Facades\Storage;

class YuvaSliderController extends Controller
{
    public function index()
    {
        return response()->json(YuvaSlider::latest()->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:200', // 200 KB
        ]);

        if (YuvaSlider::count() >= 5) {
            return response()->json(['error' => 'You can only upload max 5 images'], 422);
        }

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('yuva_slider', 'public');

            $slider = YuvaSlider::create([
                'image' => '/storage/' . $path,
            ]);

            return response()->json($slider, 201);
        }

        return response()->json(['error' => 'Image upload failed'], 422);
    }

    public function destroy($id)
    {
        $slider = YuvaSlider::findOrFail($id);

        if ($slider->image && file_exists(public_path($slider->image))) {
            unlink(public_path($slider->image));
        }

        $slider->delete();

        return response()->json(['message' => 'Image deleted successfully']);
    }
}
