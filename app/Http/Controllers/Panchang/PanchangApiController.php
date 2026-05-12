<?php

namespace App\Http\Controllers\Panchang;

use App\Http\Controllers\Controller;
use App\Models\DailyPanchang;
use Illuminate\Http\Request;

class PanchangApiController extends Controller
{
    /**
     * GET /api/panchang
     * Saare records date wise (latest pehle), ek array mein
     * Optional query params:
     *   ?date=2026-05-15         → sirf us date ka record
     *   ?from=2026-05-01&to=2026-05-31 → date range
     */
    public function index(Request $request)
    {
        $query = DailyPanchang::query()->orderBy('date', 'desc');

        // Single date filter
        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        // Date range filter
        if ($request->filled('from')) {
            $query->whereDate('date', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('date', '<=', $request->to);
        }

        $records = $query->get()->map(function ($p) {
            return [
                'date'            => $p->date->format('Y-m-d'),
                'lunar_month'     => $p->lunar_month_name,
                'vikram_samvat'   => $p->vikram_samvat,
                'tithi_number'    => $p->tithi_number,
                'tithi'           => $p->tithi,
                'tithi_two'       => $p->tithi_two,
                'paksha'          => $p->paksha,
            ];
        });

        return response()->json([
            'success' => true,
            'count'   => $records->count(),
            'data'    => $records,
        ]);
    }

    /**
     * GET /api/panchang/today
     * Sirf aaj ki date ka record
     */
    public function today()
    {
        $today  = now()->timezone('Asia/Kolkata')->format('Y-m-d');
        $record = DailyPanchang::whereDate('date', $today)->first();

        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'Aaj (' . $today . ') ka panchang data available nahi hai.',
                'data'    => null,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => [
                'date'          => $record->date->format('Y-m-d'),
                'lunar_month'   => $record->lunar_month_name,
                'vikram_samvat' => $record->vikram_samvat,
                'tithi_number'  => $record->tithi_number,
                'tithi'         => $record->tithi,
                'tithi_two'     => $record->tithi_two,
                'paksha'        => $record->paksha,
            ],
        ]);
    }
}
