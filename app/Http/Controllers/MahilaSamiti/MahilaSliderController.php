<?php

namespace App\Http\Controllers\MahilaSamiti;

use App\Http\Controllers\Controller;
use App\Models\MahilaSamiti\MahilaSlider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MahilaSliderController extends Controller
{
    public function index()
    {
        return response()->json(MahilaSlider::all());
    }

    public function store(Request $request)
{
    // पहले check करो कि DB में पहले से कितनी photos हैं
    $count = MahilaSlider::count();
    if ($count >= 5) {
        return response()->json(['error' => 'Maximum 5 photos allowed in slider!'], 422);
    }

    $validator = Validator::make($request->all(), [
        'photos.*' => 'required|image|max:200', // हर photo ≤ 200KB
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()->first()], 422);
    }

    $uploaded = [];
    if ($request->hasFile('photos')) {
        // बची हुई जगह कितनी है calculate करो
        $remaining = 5 - $count;
        $files = array_slice($request->file('photos'), 0, $remaining);

        foreach ($files as $photo) {
            $path = $photo->store('mahila_slider', 'public');
            $slider = MahilaSlider::create([
                'photo' => '/storage/' . $path,
            ]);
            $uploaded[] = $slider;
        }

        if (count($request->file('photos')) > $remaining) {
            return response()->json([
                'success' => 'Some photos uploaded successfully',
                'warning' => 'Slider can hold only 5 photos. Extra files ignored.',
                'data' => $uploaded
            ]);
        }
    }

    return response()->json(['success' => 'Photos uploaded successfully', 'data' => $uploaded]);
}

    public function update(Request $request, $id)
    {
        $slider = MahilaSlider::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'photo' => 'nullable|image|max:200',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        if ($request->hasFile('photo')) {
            if ($slider->photo) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $slider->photo));
            }

            $path = $request->file('photo')->store('mahila_slider', 'public');
            $slider->photo = '/storage/' . $path;
        }

        $slider->save();

        return response()->json(['success' => 'Photo updated successfully', 'data' => $slider]);
    }

    public function destroy($id)
    {
        $slider = MahilaSlider::findOrFail($id);
        if ($slider->photo) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $slider->photo));
        }
        $slider->delete();

        return response()->json(['success' => 'Photo deleted successfully']);
    }
}
