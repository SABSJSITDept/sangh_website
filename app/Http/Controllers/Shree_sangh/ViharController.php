<?php

// app/Http/Controllers/ShreeSangh/ViharController.php

namespace App\Http\Controllers\Shree_sangh;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShreeSangh\Vihar;

class ViharController extends Controller
{
  public function index()
{
    $vihars = Vihar::orderBy('created_at', 'desc')
                   ->take(14)
                   ->get();

    $vihars->map(function ($item) {
        $item->formatted_date = $item->created_at->format('d-m-Y');
        return $item;
    });

    return response()->json($vihars);
}


    public function store(Request $request)
    {
        $vihar = Vihar::create($request->validate([
            'location' => 'required|string',
        ]));

        return response()->json($vihar);
    }

    public function update(Request $request, $id)
    {
        $vihar = Vihar::findOrFail($id);
        $vihar->update($request->validate([
            'location' => 'required|string',
        ]));

        return response()->json($vihar);
    }

    public function destroy($id)
    {
        Vihar::destroy($id);
        return response()->json(['message' => 'Deleted successfully']);
    }
    public function latest()
{
    $vihar = Vihar::orderBy('created_at', 'desc')->first();

    if ($vihar) {
        $vihar->formatted_date = $vihar->created_at->format('d-m-Y');
    }

    return response()->json($vihar);
}

}
