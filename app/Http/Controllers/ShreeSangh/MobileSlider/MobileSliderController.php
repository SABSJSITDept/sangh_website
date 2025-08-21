<?php

namespace App\Http\Controllers\ShreeSangh\MobileSlider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShreeSangh\MobileSlider\MobileSlider;
use Illuminate\Support\Facades\Storage;

class MobileSliderController extends Controller
{
    public function index()
    {
        return response()->json(MobileSlider::latest()->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:200', // 200KB
        ]);

        $path = $request->file('image')->store('public/mobile_slider');

        $slider = MobileSlider::create([
            'image' => Storage::url($path),
        ]);

        return response()->json(['message' => 'Image uploaded successfully!', 'data' => $slider], 201);
    }

    public function update(Request $request, $id)
    {
        $slider = MobileSlider::findOrFail($id);

        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'image|max:200',
            ]);

            // delete old file
            if ($slider->image && file_exists(public_path($slider->image))) {
                unlink(public_path($slider->image));
            }

            $path = $request->file('image')->store('public/mobile_slider');
            $slider->image = Storage::url($path);
        }

        $slider->save();

        return response()->json(['message' => 'Image updated successfully!', 'data' => $slider]);
    }

    public function destroy($id)
    {
        $slider = MobileSlider::findOrFail($id);

        if ($slider->image && file_exists(public_path($slider->image))) {
            unlink(public_path($slider->image));
        }

        $slider->delete();

        return response()->json(['message' => 'Image deleted successfully!']);
    }
}
