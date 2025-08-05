<?php

namespace App\Http\Controllers\Shree_Sangh;

use App\Models\ShreeSangh\DailyThought;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ThoughtApiController extends Controller
{
   // app/Http/Controllers/ShreeSangh/ThoughtApiController.php

 public function index()
{
    return response()->json(
        \App\Models\ShreeSangh\DailyThought::latest()
            ->paginate(14, ['id', 'thought', 'created_at'])
    );
}

    public function latestThought()
    {
        $latest = DailyThought::latest()->first(['id', 'thought', 'created_at']);

        if ($latest) {
            return response()->json($latest);
        }

        return response()->json(['thought' => null, 'created_at' => null]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'thought' => 'required|string',
        ]);

        $thought = DailyThought::create([
            'thought' => $request->thought,
        ]);

        return response()->json($thought);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'thought' => 'required|string',
        ]);

        $thought = DailyThought::findOrFail($id);
        $thought->update(['thought' => $request->thought]);

        return response()->json($thought);
    }

    public function destroy($id)
    {
        $thought = DailyThought::findOrFail($id);
        $thought->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }
}