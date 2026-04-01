<?php

namespace App\Http\Controllers\Status;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Status\Status;

class StatusController extends Controller
{
    public function index()
    {
        return response()->json(Status::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'status' => 'required|in:0,1',
        ]);

        $status = Status::create($validated);
        return response()->json($status, 201);
    }

    public function update(Request $request, $id)
    {
        $statusRecord = Status::findOrFail($id);

        $validated = $request->validate([
            'name'   => 'sometimes|required|string|max:255',
            'status' => 'sometimes|required|in:0,1',
        ]);

        $statusRecord->update($validated);
        return response()->json($statusRecord);
    }

    public function destroy($id)
    {
        $statusRecord = Status::findOrFail($id);
        $statusRecord->delete();

        return response()->json(['success' => true]);
    }
}
