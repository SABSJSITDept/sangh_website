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
        return response()->json(News::latest()->take(10)->get());
    }

   public function store(Request $request)
{
    $request->validate([
        'title'       => 'required|string',
        'description' => 'required|string',
        'date'        => 'nullable|date',
        'time'        => 'nullable|string',
        'location'    => 'nullable|string',
        'photo'       => 'nullable|image|max:200' // 20KB
    ]);

    $data = $request->only(['title', 'date', 'time', 'location', 'description']);

    if ($request->hasFile('photo')) {
        $data['photo'] = $request->file('photo')->store('news', 'public');
    }

    $news = News::create($data);

    return response()->json($news);
}

    public function update(Request $request, $id)
{
    $news = News::findOrFail($id);

    $data = $request->validate([
        'title'       => 'required|string',
        'description' => 'required|string',
        'date'        => 'nullable|date',
        'time'        => 'nullable|string',
        'location'    => 'nullable|string',
        'photo'       => 'nullable|image|max:200' // 20KB
    ]);

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
