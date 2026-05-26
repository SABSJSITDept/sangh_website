<?php

// app/Http/Controllers/ShreeSangh/ViharController.php

namespace App\Http\Controllers\Shree_sangh;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShreeSangh\Vihar;

class ViharController extends Controller
{
    public function index(Request $request)
    {
        $query = Vihar::query();

        if ($request->has('date')) {
            $query->where('date', $request->query('date'));
        }

        $vihars = $query->orderByRaw('COALESCE(date, DATE(created_at)) DESC')
            ->take(14)
            ->get();

        $vihars->map(function ($item) {
            $item->formatted_date = $item->date ? \Carbon\Carbon::parse($item->date)->format('d-m-Y') : $item->created_at->format('d-m-Y');
            return $item;
        });

        return response()->json($vihars);
    }


    public function store(Request $request)
    {
        $today = \Carbon\Carbon::today()->toDateString();
        $tomorrow = \Carbon\Carbon::tomorrow()->toDateString();

        $validated = $request->validate([
            'location' => 'required|string',
            'location_link' => 'nullable|url',
            'date' => 'required|date|in:' . $today . ',' . $tomorrow . '|unique:vihar,date',
        ]);

        $vihar = Vihar::create($validated);

        return response()->json($vihar);
    }

    public function update(Request $request, $id)
    {
        $today = \Carbon\Carbon::today()->toDateString();
        $tomorrow = \Carbon\Carbon::tomorrow()->toDateString();

        $validated = $request->validate([
            'location' => 'required|string',
            'location_link' => 'nullable|url',
            'date' => 'required|date|in:' . $today . ',' . $tomorrow . '|unique:vihar,date,' . $id,
        ]);

        $vihar = Vihar::findOrFail($id);
        $vihar->update($validated);

        return response()->json($vihar);
    }

    public function destroy($id)
    {
        Vihar::destroy($id);
        return response()->json(['message' => 'Deleted successfully']);
    }

    public function latest(Request $request)
    {
        $date = $request->query('date');
        
        if ($date) {
            $vihar = Vihar::where('date', $date)->first();
        } else {
            $today = \Carbon\Carbon::today()->toDateString();
            $vihar = Vihar::where('date', $today)->first();
            
            if (!$vihar) {
                $vihar = Vihar::orderByRaw('COALESCE(date, DATE(created_at)) DESC')->first();
            }
        }

        if ($vihar) {
            $vihar->formatted_date = $vihar->date ? \Carbon\Carbon::parse($vihar->date)->format('d-m-Y') : $vihar->created_at->format('d-m-Y');
        }

        return response()->json($vihar);
    }

}
