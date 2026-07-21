<?php

namespace App\Http\Controllers\SanghSahitya\Shramnopasak\daily_news;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DailyNewsController extends Controller
{
    public function index()
    {
        return view('dashboards.shramnopasak_news.daily_news');
    }

    public function fetchNews(\Illuminate\Http\Request $request)
    {
        $query = \App\Models\SanghSahitya\Shramnopasak\daily_news\DailyNews::orderBy('created_at', 'desc');
        
        if ($request->has('anchal_id')) {
            $query->where('anchal_id', $request->anchal_id);
        }

        $news = $query->paginate(10);
        return response()->json($news);
    }

    public function like($id)
    {
        $news = \App\Models\SanghSahitya\Shramnopasak\daily_news\DailyNews::find($id);
        if (!$news) {
            return response()->json(['status' => 'error', 'message' => 'News not found'], 404);
        }

        $news->increment('like_count');
        
        return response()->json([
            'status' => 'success',
            'message' => 'Liked successfully',
            'like_count' => $news->like_count
        ]);
    }

    // Admin Panel CRUD methods

    public function store(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->except(['photo']);

        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $imageName = time() . '.' . $image->extension();
            $image->move(public_path('uploads/shramnopasak_news'), $imageName);
            $data['photo'] = 'uploads/shramnopasak_news/' . $imageName;
        }

        \App\Models\SanghSahitya\Shramnopasak\daily_news\DailyNews::create($data);

        return response()->json(['success' => true, 'message' => 'News added successfully!']);
    }

    public function fetch()
    {
        $news = \App\Models\SanghSahitya\Shramnopasak\daily_news\DailyNews::orderBy('created_at', 'desc')->get();
        return response()->json(['data' => $news]);
    }

    public function update(\Illuminate\Http\Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $news = \App\Models\SanghSahitya\Shramnopasak\daily_news\DailyNews::findOrFail($id);
        $data = $request->except(['photo']);

        if ($request->hasFile('photo')) {
            if ($news->photo && file_exists(public_path($news->photo))) {
                unlink(public_path($news->photo));
            }
            $image = $request->file('photo');
            $imageName = time() . '.' . $image->extension();
            $image->move(public_path('uploads/shramnopasak_news'), $imageName);
            $data['photo'] = 'uploads/shramnopasak_news/' . $imageName;
        }

        $news->update($data);

        return response()->json(['success' => true, 'message' => 'News updated successfully!']);
    }

    public function destroy($id)
    {
        $news = \App\Models\SanghSahitya\Shramnopasak\daily_news\DailyNews::findOrFail($id);
        
        if ($news->photo && file_exists(public_path($news->photo))) {
            unlink(public_path($news->photo));
        }
        
        $news->delete();

        return response()->json(['success' => true, 'message' => 'News deleted successfully!']);
    }
}
