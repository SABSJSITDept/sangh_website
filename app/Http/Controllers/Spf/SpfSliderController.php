<?php

namespace App\Http\Controllers\Spf;

use App\Http\Controllers\Controller;
use App\Models\Spf\SpfSlider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SpfSliderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sliders = SpfSlider::all()->map(function ($slider) {
            return [
                'id' => $slider->id,
                'image' => Storage::url($slider->image),
                'created_at' => $slider->created_at,
                'updated_at' => $slider->updated_at,
            ];
        });

        return response()->json($sliders, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'image' => 'required|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('spf_slider', 'public');
            $validated['image'] = $path;
        }

        $slider = SpfSlider::create($validated);

        return response()->json([
            'data' => [
                'id' => $slider->id,
                'image' => Storage::url($slider->image),
            ],
            'message' => 'Slider image uploaded successfully'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(SpfSlider $spfSlider)
    {
        return response()->json([
            'data' => [
                'id' => $spfSlider->id,
                'image' => Storage::url($spfSlider->image),
            ]
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SpfSlider $spfSlider)
    {
        $validated = $request->validate([
            'image' => 'required|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($spfSlider->image && Storage::disk('public')->exists($spfSlider->image)) {
                Storage::disk('public')->delete($spfSlider->image);
            }

            $path = $request->file('image')->store('spf_slider', 'public');
            $validated['image'] = $path;
        }

        $spfSlider->update($validated);

        return response()->json([
            'data' => [
                'id' => $spfSlider->id,
                'image' => Storage::url($spfSlider->image),
            ],
            'message' => 'Slider image updated successfully'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SpfSlider $spfSlider)
    {
        // Delete image file
        if ($spfSlider->image && Storage::disk('public')->exists($spfSlider->image)) {
            Storage::disk('public')->delete($spfSlider->image);
        }

        $spfSlider->delete();

        return response()->json([
            'message' => 'Slider image deleted successfully'
        ], 200);
    }
}
