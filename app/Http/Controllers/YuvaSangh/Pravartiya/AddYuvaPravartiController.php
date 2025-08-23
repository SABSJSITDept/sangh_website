<?php

namespace App\Http\Controllers\YuvaSangh\Pravartiya;

use App\Http\Controllers\Controller;
use App\Models\YuvaSangh\Pravartiya\YuvaPravartiya;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AddYuvaPravartiController extends Controller
{
    // GET /api/yuva-pravartiya
    public function index()
    {
        return response()->json(
            YuvaPravartiya::orderByDesc('created_at')->get()
        );
    }

    // POST /api/yuva-pravartiya
    public function store(Request $request)
    {
        $validated = $request->validate([
            'heading' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            // 200KB, image only
            'photo'   => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp,gif', 'max:200'],
        ]);

        // optional photo
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('yuva_pravartiya', 'public'); // storage/app/public/yuva_pravartiya
            $validated['photo'] = '/storage/' . $path;
        }

        $item = YuvaPravartiya::create($validated);

        return response()->json([
            'message' => 'Entry created successfully.',
            'data' => $item
        ], 201);
    }

    // GET /api/yuva-pravartiya/{id}
    public function show(YuvaPravartiya $yuva_pravartiya)
    {
        return response()->json($yuva_pravartiya);
    }

    // PUT/PATCH /api/yuva-pravartiya/{id}
    public function update(Request $request, YuvaPravartiya $yuva_pravartiya)
    {
        $validated = $request->validate([
            'heading' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'photo'   => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp,gif', 'max:200'],
        ]);

        if ($request->hasFile('photo')) {
            // delete old file if exists
            if ($yuva_pravartiya->photo) {
                $old = Str::replaceFirst('/storage/', '', $yuva_pravartiya->photo);
                Storage::disk('public')->delete($old);
            }
            $path = $request->file('photo')->store('yuva_pravartiya', 'public');
            $validated['photo'] = '/storage/' . $path;
        }

        $yuva_pravartiya->update($validated);

        return response()->json([
            'message' => 'Entry updated successfully.',
            'data' => $yuva_pravartiya
        ]);
    }

    // DELETE /api/yuva-pravartiya/{id}
    public function destroy(YuvaPravartiya $yuva_pravartiya)
    {
        if ($yuva_pravartiya->photo) {
            $old = Str::replaceFirst('/storage/', '', $yuva_pravartiya->photo);
            Storage::disk('public')->delete($old);
        }

        $yuva_pravartiya->delete();

        return response()->json(['message' => 'Entry deleted successfully.']);
    }
}
