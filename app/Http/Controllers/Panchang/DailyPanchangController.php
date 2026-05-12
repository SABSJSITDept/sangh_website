<?php

namespace App\Http\Controllers\Panchang;

use App\Http\Controllers\Controller;
use App\Models\DailyPanchang;
use Illuminate\Http\Request;

class DailyPanchangController extends Controller
{
    /**
     * Panchang list - date wise paginated
     */
    public function index(Request $request)
    {
        $query = DailyPanchang::query()->orderBy('date', 'desc');

        // Date filter agar search kiya ho
        if ($request->filled('search_date')) {
            $query->where('date', $request->search_date);
        }

        $panchangs = $query->paginate(15)->withQueryString();

        return view('dashboards.shree_sangh.daily_panchang', compact('panchangs'));
    }
}
