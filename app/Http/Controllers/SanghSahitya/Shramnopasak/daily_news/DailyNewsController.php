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
}
