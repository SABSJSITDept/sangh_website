<?php

namespace App\Http\Controllers\SanghSahitya\Shramnopasak\news_comments;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NewsCommentsController extends Controller
{
    public function index()
    {
        return view('dashboards.shramnopasak_news.news_comments');
    }
}
