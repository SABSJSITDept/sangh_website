<?php

// app/Http/Controllers/Shree_sangh/News/NewsUpdateController.php
namespace App\Http\Controllers\Shree_sangh\News;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShreeSangh\News\News;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsUpdateController extends Controller
{
    public function index()
    {
        return response()->json(News::orderBy('date', 'desc')->take(10)->get());
    }

   public function store(Request $request)
{
    $rules = [
        'title'       => 'required|string',
        'description' => 'required|string',
        'date'        => 'nullable|date',
        'time'        => 'nullable|string',
        'location'    => 'nullable|string',
        'mode'        => 'required|in:online,offline',
        'location_link' => 'nullable|url',
        'photo'       => 'nullable|image|max:200' // 200KB
    ];

    // If mode is offline, location_link is required
    if ($request->input('mode') === 'offline') {
        $rules['location_link'] = 'required|url';
    }

    $request->validate($rules);

    $data = $request->only(['title', 'date', 'time', 'location', 'mode', 'location_link', 'description']);

    if ($request->hasFile('photo')) {
        $data['photo'] = $request->file('photo')->store('news', 'public');
    }

    $news = News::create($data);

    return response()->json($news);
}

    public function update(Request $request, $id)
{
    $news = News::findOrFail($id);

    $rules = [
        'title'       => 'required|string',
        'description' => 'required|string',
        'date'        => 'nullable|date',
        'time'        => 'nullable|string',
        'location'    => 'nullable|string',
        'mode'        => 'required|in:online,offline',
        'location_link' => 'nullable|url',
        'photo'       => 'nullable|image|max:200' // 200KB
    ];

    // If mode is offline, location_link is required
    if ($request->input('mode') === 'offline') {
        $rules['location_link'] = 'required|url';
    }

    $data = $request->validate($rules);

    if ($request->hasFile('photo')) {
        Storage::disk('public')->delete($news->photo);
        $data['photo'] = $request->file('photo')->store('news', 'public');
    }

    $news->update($data);

    return response()->json($news);
}


   public function destroy($id)
{
    $news = News::findOrFail($id);

    if (!empty($news->photo) && Storage::disk('public')->exists($news->photo)) {
        Storage::disk('public')->delete($news->photo);
    }

    $news->delete();

    return response()->json(['message' => 'Deleted']);
}


    public function show($id)
    {
        return response()->json(News::findOrFail($id));
    }
}
