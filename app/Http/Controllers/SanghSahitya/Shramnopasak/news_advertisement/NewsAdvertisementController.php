<?php

namespace App\Http\Controllers\SanghSahitya\Shramnopasak\news_advertisement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NewsAdvertisementController extends Controller
{
    public function index()
    {
        return view('dashboards.shramnopasak_news.news_advertisement');
    }
}
