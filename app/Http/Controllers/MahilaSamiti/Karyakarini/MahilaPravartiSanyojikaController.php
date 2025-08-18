<?php

namespace App\Http\Controllers\MahilaSamiti\Karyakarini;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MahilaSamiti\Karyakarini\MahilaPravartiSanyojika;
use Illuminate\Support\Facades\Validator;

class MahilaPravartiSanyojikaController extends Controller
{
    // Fetch all members
    public function index()
    {
        return response()->json(MahilaPravartiSanyojika::all());
    }

    // Store new member
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            'city'      => 'nullable|string|max:255',
            'post'      => 'required|string',
            'pravarti'  => 'required|string',
            'mobile'    => 'required|digits:10',
            'photo'     => 'required|image|max:200', // 200KB
        ]);

        if($validator->fails()){
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $path = $request->file('photo')->store('mahila_pravarti_sanyojika', 'public');

        $data = MahilaPravartiSanyojika::create([
            'name'      => $request->name,
            'city'      => $request->city,
            'post'      => $request->post,
            'pravarti'  => $request->pravarti,
            'mobile'    => $request->mobile,
            'photo'     => '/storage/'.$path,
        ]);

        return response()->json($data, 201);
    }

    // Show single member
    public function show($id)
    {
        return response()->json(MahilaPravartiSanyojika::findOrFail($id));
    }

    // Update member
    public function update(Request $request, $id)
    {
        $member = MahilaPravartiSanyojika::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            'city'      => 'nullable|string|max:255',
            'post'      => 'required|string',
            'pravarti'  => 'required|string',
            'mobile'    => 'required|digits:10',
            'photo'     => 'nullable|image|max:200',
        ]);

        if($validator->fails()){
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if($request->hasFile('photo')){
            if($member->photo && file_exists(public_path($member->photo))){
                unlink(public_path($member->photo));
            }
            $path = $request->file('photo')->store('mahila_pravarti_sanyojika', 'public');
            $member->photo = '/storage/'.$path;
        }

        $member->update($request->only(['name','city','post','pravarti','mobile']));
        $member->save();

        return response()->json($member);
    }

    // Delete member
    public function destroy($id)
    {
        $member = MahilaPravartiSanyojika::findOrFail($id);
        if($member->photo && file_exists(public_path($member->photo))){
            unlink(public_path($member->photo));
        }
        $member->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }

    // Pravarti-wise members
   public function pravartiWise($slug)
{
    // Convert hyphens/underscores to spaces
    $pravarti = str_replace(['-', '_'], ' ', $slug);

    $members = MahilaPravartiSanyojika::where('pravarti', $pravarti)->get();
    return response()->json($members);
}

}
