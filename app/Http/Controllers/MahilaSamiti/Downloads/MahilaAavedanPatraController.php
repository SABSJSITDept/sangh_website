<?php

namespace App\Http\Controllers\MahilaSamiti\Downloads;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MahilaSamiti\Downloads\MahilaAavedanPatra;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MahilaAavedanPatraController extends Controller
{
    public function index()
    {
        return response()->json(MahilaAavedanPatra::all());
    }

    public function store(Request $request)
    {
       $validator = Validator::make($request->all(), [
    'name'  => 'required|string|max:255',
    'type'  => 'required|in:pdf,google_form',
    'pdf'   => 'required_if:type,pdf|mimes:pdf|max:2048',
    'google_form_link' => 'nullable|required_if:type,google_form|url'
]);


        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()],422);
        }

        $data = $request->only(['name', 'type', 'google_form_link']);

        if ($request->type === 'pdf' && $request->hasFile('pdf')) {
            $data['pdf'] = $request->file('pdf')->store('mahila_aavedan_patra','public');
        }

        $patra = MahilaAavedanPatra::create($data);

        return response()->json(['message'=>'Added successfully','data'=>$patra],201);
    }

    public function update(Request $request, $id)
    {
        $patra = MahilaAavedanPatra::findOrFail($id);

      $validator = Validator::make($request->all(), [
    'name'  => 'required|string|max:255',
    'type'  => 'required|in:pdf,google_form',
    'pdf'   => 'nullable|required_if:type,pdf|mimes:pdf|max:2048',
    'google_form_link' => 'nullable|required_if:type,google_form|url'
]);


        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()],422);
        }

        $data = $request->only(['name', 'type', 'google_form_link']);

        if ($request->type === 'pdf' && $request->hasFile('pdf')) {
            if ($patra->pdf) {
                Storage::disk('public')->delete($patra->pdf);
            }
            $data['pdf'] = $request->file('pdf')->store('mahila_aavedan_patra','public');
        }

        $patra->update($data);

        return response()->json(['message'=>'Updated successfully','data'=>$patra]);
    }

    public function destroy($id)
    {
        $patra = MahilaAavedanPatra::findOrFail($id);
        if ($patra->pdf) {
            Storage::disk('public')->delete($patra->pdf);
        }
        $patra->delete();

        return response()->json(['message'=>'Deleted successfully']);
    }

    // सिर्फ Google Form वाली entries
public function offlineForms()
{
    $data = MahilaAavedanPatra::where('type', 'pdf')->get();
    return response()->json($data);
}

public function onlineForms()
{
    $data = MahilaAavedanPatra::where('type', 'google_form')->get();
    return response()->json($data);
}



// public function show($id)
// {
//     $patra = MahilaAavedanPatra::findOrFail($id);
//     return response()->json($patra);
// }

}
