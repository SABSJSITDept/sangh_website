<?php

namespace App\Http\Controllers\Panchang;

use App\Http\Controllers\Controller;
use App\Models\DailyPanchang;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DailyPanchangController extends Controller
{
    private const API_URL = 'https://api.freeastroapi.com/api/v2/vedic/panchang';
    private const API_KEY = 'e7fb55658b8268b6b20379377703884983187bf610210977cf7fa9fb4dbb78fb';

    /**
     * Panchang list — date wise paginated
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

    /**
     * Kisi bhi date ke liye manually API call karke fetch karo
     */
    public function fetchForDate(Request $request)
    {
        $request->validate([
            'fetch_date' => 'required|date|date_format:Y-m-d',
        ], [
            'fetch_date.required'     => 'Kripya ek date select karein.',
            'fetch_date.date'         => 'Date galat format mein hai.',
            'fetch_date.date_format'  => 'Date format YYYY-MM-DD honi chahiye.',
        ]);

        $targetDate = Carbon::parse($request->fetch_date);
        $dateString = $targetDate->format('Y-m-d');

        // Pehle check karo kya already exist karta hai
        $existing = DailyPanchang::where('date', $dateString)->first();

        if ($existing && !$request->boolean('force_refetch')) {
            return redirect()
                ->route('daily.panchang')
                ->with('warning', "⚠️ {$dateString} ka panchang already database mein hai. Dobara fetch karne ke liye 'Force Refetch' checkbox tick karein.")
                ->withInput(['search_date' => $dateString]);
        }

        // API Request Body — time hamesha 12:00 noon
        $requestBody = [
            'year'         => (int) $targetDate->format('Y'),
            'month'        => (int) $targetDate->format('m'),
            'day'          => (int) $targetDate->format('d'),
            'hour'         => 12,
            'minute'       => 0,
            'city'         => 'Bikaner',
            'lat'          => 28.0271,
            'lng'          => 73.3022,
            'tz_str'       => 'Asia/Kolkata',
            'ayanamsha'    => 'lahiri',
            'house_system' => 'whole_sign',
            'node_type'    => 'mean',
        ];

        try {
            $response = Http::timeout(15)
                ->withHeaders([
                    'x-api-key'    => self::API_KEY,
                    'Content-Type' => 'application/json',
                    'Accept'       => 'application/json',
                ])
                ->post(self::API_URL, $requestBody);

            if (!$response->successful()) {
                Log::error('PanchangFetch (Manual): API failed', [
                    'status'   => $response->status(),
                    'body'     => $response->body(),
                    'date'     => $dateString,
                ]);

                return redirect()
                    ->route('daily.panchang')
                    ->with('error', "❌ API Error (Status {$response->status()}): Panchang fetch karne mein dikkat aayi. Dobara try karein.");
            }

            $data = $response->json();

            $record = DailyPanchang::updateOrCreate(
                ['date' => $data['date'] ?? $dateString],
                [
                    'lunar_month_name' => $data['lunar_month']['name']                    ?? null,
                    'vikram_samvat'    => $data['lunar_month']['vikram_samvat']            ?? null,
                    'tithi_number'     => $data['tithi']['number']                         ?? null,
                    'tithi'            => $data['tithi']['name']                           ?? null,
                    'paksha'           => $data['tithi']['paksha']                         ?? null,
                    'tithi_two'        => $data['request_time_panchang']['tithi']['name']  ?? null,
                ]
            );

            $action = $existing ? 'Update' : 'Fetch';
            Log::info("PanchangFetch (Manual {$action}): Success", ['date' => $dateString]);

            return redirect()
                ->route('daily.panchang', ['search_date' => $dateString])
                ->with('success', "✅ {$dateString} ka panchang successfully " . strtolower($action) . " ho gaya!");

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('PanchangFetch (Manual): Connection failed', ['message' => $e->getMessage()]);
            return redirect()
                ->route('daily.panchang')
                ->with('error', '❌ API se connect nahi ho paya. Internet/server check karein.');

        } catch (\Throwable $e) {
            Log::error('PanchangFetch (Manual): Exception', ['message' => $e->getMessage()]);
            return redirect()
                ->route('daily.panchang')
                ->with('error', '❌ Kuch gadbad ho gayi: ' . $e->getMessage());
        }
    }

    /**
     * Kisi record ko delete karo
     */
    public function destroy($id)
    {
        $record = DailyPanchang::findOrFail($id);
        $date   = $record->date->format('Y-m-d');
        $record->delete();

        return redirect()
            ->route('daily.panchang')
            ->with('success', "🗑️ {$date} ka panchang record delete ho gaya.");
    }
}
