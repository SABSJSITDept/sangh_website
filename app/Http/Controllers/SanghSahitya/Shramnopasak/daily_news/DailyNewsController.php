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
}
