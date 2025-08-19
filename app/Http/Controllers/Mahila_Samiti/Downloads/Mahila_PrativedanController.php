<?php

namespace App\Http\Controllers\Mahila_Samiti\Downloads;

use App\Http\Controllers\Controller;
use App\Models\Mahila_Samiti\Downloads\Mahila_Prativedan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Mahila_PrativedanController extends Controller
{
    public function index()
{
    return response()->json(Mahila_Prativedan::latest()->get());
}


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'google_drive_link' => 'required|url'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 422);
        }

        $data = Mahila_Prativedan::create($request->all());
        return response()->json($data, 201);
    }

    public function show($id)
    {
        return response()->json(Mahila_Prativedan::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $prativedan = Mahila_Prativedan::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'google_drive_link' => 'required|url'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 422);
        }

        $prativedan->update($request->all());
        return response()->json($prativedan);
    }

    public function destroy($id)
    {
        $prativedan = Mahila_Prativedan::findOrFail($id);
        $prativedan->delete();
        return response()->json(['message'=>'Deleted successfully']);
    }
}
