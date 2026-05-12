<?php

namespace App\Console\Commands;

use App\Models\DailyPanchang;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FetchDailyPanchang extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'panchang:fetch {--date= : Optional date in Y-m-d format to fetch for a specific day}';

    /**
     * The console command description.
     */
    protected $description = 'Fetch daily Vedic Panchang data from freeastroapi.com and store in database';

    /**
     * Panchang API endpoint.
     */
    private const API_URL = 'https://api.freeastroapi.com/api/v2/vedic/panchang';

    /**
     * API key for authentication.
     */
    private const API_KEY = 'e7fb55658b8268b6b20379377703884983187bf610210977cf7fa9fb4dbb78fb';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // Agar --date option diya ho toh woh use karo, warna aaj ki date
        $targetDate = $this->option('date')
            ? Carbon::parse($this->option('date'))
            : Carbon::now('Asia/Kolkata');

        // Time hamesha 12:00 (noon) rehni chahiye jaise request ne bataya
        $year   = (int) $targetDate->format('Y');
        $month  = (int) $targetDate->format('m');
        $day    = (int) $targetDate->format('d');
        $hour   = 12;
        $minute = 0;

        $dateString = $targetDate->format('Y-m-d');

        $this->info("Fetching Panchang for date: {$dateString} at {$hour}:{$minute}");

        // Check karo kya is date ka record pehle se hai
        if (DailyPanchang::where('date', $dateString)->exists()) {
            $this->warn("Panchang for {$dateString} already exists in database. Skipping.");
            return Command::SUCCESS;
        }

        // API request body
        $requestBody = [
            'year'         => $year,
            'month'        => $month,
            'day'          => $day,
            'hour'         => $hour,
            'minute'       => $minute,
            'city'         => 'Bikaner',
            'lat'          => 28.0271,
            'lng'          => 73.3022,
            'tz_str'       => 'Asia/Kolkata',
            'ayanamsha'    => 'lahiri',
            'house_system' => 'whole_sign',
            'node_type'    => 'mean',
        ];

        try {
            $response = Http::withHeaders([
                'x-api-key'    => self::API_KEY,
                'Content-Type' => 'application/json',
                'Accept'       => 'application/json',
            ])->post(self::API_URL, $requestBody);

            if (!$response->successful()) {
                $this->error("API request failed. Status: {$response->status()}");
                $this->error("Response: {$response->body()}");
                Log::error('PanchangFetch: API request failed', [
                    'status'   => $response->status(),
                    'response' => $response->body(),
                    'date'     => $dateString,
                ]);
                return Command::FAILURE;
            }

            $data = $response->json();

            // Required fields extract karo
            $date            = $data['date']                                        ?? $dateString;
            $lunarMonthName  = $data['lunar_month']['name']                         ?? null;
            $vikramSamvat    = $data['lunar_month']['vikram_samvat']                ?? null;
            $tithiNumber     = $data['tithi']['number']                             ?? null;
            $tithi           = $data['tithi']['name']                               ?? null;
            $paksha          = $data['tithi']['paksha']                             ?? null;
            $tithiTwo        = $data['request_time_panchang']['tithi']['name']      ?? null;

            // Database mein save karo (updateOrCreate - duplicate avoid karne ke liye)
            DailyPanchang::updateOrCreate(
                ['date' => $date],
                [
                    'lunar_month_name' => $lunarMonthName,
                    'vikram_samvat'    => $vikramSamvat,
                    'tithi_number'     => $tithiNumber,
                    'tithi'            => $tithi,
                    'paksha'           => $paksha,
                    'tithi_two'        => $tithiTwo,
                ]
            );

            $this->info("✓ Panchang saved successfully for {$date}");
            $this->table(
                ['Field', 'Value'],
                [
                    ['Date',            $date],
                    ['Lunar Month',     $lunarMonthName],
                    ['Vikram Samvat',   $vikramSamvat],
                    ['Tithi Number',    $tithiNumber],
                    ['Tithi',           $tithi],
                    ['Paksha',          $paksha],
                    ['Tithi Two',       $tithiTwo],
                ]
            );

            Log::info('PanchangFetch: Successfully fetched and saved', [
                'date'           => $date,
                'lunar_month'    => $lunarMonthName,
                'vikram_samvat'  => $vikramSamvat,
                'tithi'          => $tithi,
                'paksha'         => $paksha,
            ]);

            return Command::SUCCESS;

        } catch (\Throwable $e) {
            $this->error("Exception occurred: {$e->getMessage()}");
            Log::error('PanchangFetch: Exception', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
                'date'    => $dateString,
            ]);
            return Command::FAILURE;
        }
    }
}
