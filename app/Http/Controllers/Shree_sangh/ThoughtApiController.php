<?php

namespace App\Http\Controllers\Shree_Sangh;

use App\Models\ShreeSangh\DailyThought;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ThoughtApiController extends Controller
{
   // app/Http/Controllers/ShreeSangh/ThoughtApiController.php

    public function index(Request $request)
    {
        $query = DailyThought::query();

        if ($request->has('date')) {
            $query->where('date', $request->query('date'));
        }

        return response()->json(
            $query->orderByRaw('COALESCE(date, DATE(created_at)) DESC')
                ->paginate(14, ['id', 'thought', 'date', 'created_at'])
        );
    }

    public function latestThought(Request $request)
    {
        $date = $request->query('date');
        
        if ($date) {
            $latest = DailyThought::where('date', $date)->first(['id', 'thought', 'date', 'created_at']);
        } else {
            $today = \Carbon\Carbon::today()->toDateString();
            $latest = DailyThought::where('date', $today)->first(['id', 'thought', 'date', 'created_at']);
            
            if (!$latest) {
                $latest = DailyThought::orderByRaw('COALESCE(date, DATE(created_at)) DESC')
                    ->first(['id', 'thought', 'date', 'created_at']);
            }
        }

        if ($latest) {
            return response()->json($latest);
        }

        return response()->json(['thought' => null, 'date' => null, 'created_at' => null]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'thought' => 'required|string',
            'date' => 'required|date|unique:daily_thoughts,date',
        ]);

        $thought = DailyThought::create([
            'thought' => $request->thought,
            'date' => $request->date,
        ]);

        return response()->json($thought);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'thought' => 'required|string',
            'date' => 'required|date|unique:daily_thoughts,date,' . $id,
        ]);

        $thought = DailyThought::findOrFail($id);
        $thought->update([
            'thought' => $request->thought,
            'date' => $request->date,
        ]);

        return response()->json($thought);
    }

    public function destroy($id)
    {
        $thought = DailyThought::findOrFail($id);
        $thought->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }
}