<?php

namespace App\Http\Controllers\HomeSlider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HomeSlider\HomeSlider;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class AddHomeSlider extends Controller
{
    public function index()
    {
        return response()->json(HomeSlider::orderBy('id', 'desc')->get());
    }

   public function store(Request $request)
{
    $count = HomeSlider::count();
    if ($count >= 5) {
        return response()->json(['message' => 'Maximum 5 slider photos allowed.'], 422);
    }

    $request->validate([
        'photo' => 'required|image|mimes:jpeg,png,jpg|max:300',
    ]);

    $path = $request->file('photo')->store('public/home_slider');
    $photoName = str_replace('public/', 'storage/', $path);

    $slider = HomeSlider::create(['photo' => $photoName]);

    return response()->json(['message' => 'Slider photo added successfully!', 'data' => $slider]);
}


    public function update(Request $request, $id)
    {
        $slider = HomeSlider::findOrFail($id);

        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:300',
        ]);

        // Delete old file
        if ($slider->photo && file_exists(public_path($slider->photo))) {
            unlink(public_path($slider->photo));
        }

        $path = $request->file('photo')->store('public/home_slider');
        $photoName = str_replace('public/', 'storage/', $path);

        $slider->update(['photo' => $photoName]);

        return response()->json(['message' => 'Slider photo updated successfully!', 'data' => $slider]);
    }

    public function destroy($id)
    {
        $slider = HomeSlider::findOrFail($id);

        if ($slider->photo && file_exists(public_path($slider->photo))) {
            unlink(public_path($slider->photo));
        }

        $slider->delete();
        return response()->json(['message' => 'Slider photo deleted successfully!']);
    }
}
