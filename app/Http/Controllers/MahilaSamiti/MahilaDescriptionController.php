<?php

namespace App\Http\Controllers\MahilaSamiti;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MahilaSamiti\MahilaDescription;

class MahilaDescriptionController extends Controller
{
    // Description fetch करना (सबसे नयी entry)
    public function index()
    {
        $desc = MahilaDescription::latest()->first();
        return response()->json($desc);
    }

    // Description save / update करना
    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required|string',
        ], [
            'description.required' => 'कृपया विवरण दर्ज करें।',
        ]);

        // पुरानी entry delete करके नई save करें (single record)
        MahilaDescription::truncate();

        $desc = MahilaDescription::create([
            'description' => $request->description,
        ]);

        return response()->json(['success' => true, 'data' => $desc]);
    }
}
