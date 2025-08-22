<?php

namespace App\Http\Controllers\YuvaSangh\Karyakarini;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\YuvaSangh\Karyakarini\YuvaPst;
use Illuminate\Support\Facades\Storage;

class AddPstController extends Controller
{
  public function index()
{
    // Custom order
    $order = ['अध्यक्ष', 'महामंत्री', 'कोषाध्यक्ष', 'सह कोषाध्यक्ष'];

    $data = YuvaPst::orderByRaw("FIELD(post, '" . implode("','", $order) . "')")->get();

    return response()->json($data);
}


    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'post'  => 'required|in:अध्यक्ष,महामंत्री,कोषाध्यक्ष,सह कोषाध्यक्ष|unique:yuva_pst,post',
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:200',
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('yuva_pst', 'public');
        }

        $pst = YuvaPst::create([
            'name'  => $request->name,
            'post'  => $request->post,
            'photo' => '/storage/' . $path,
        ]);

        return response()->json(['message' => 'Post added successfully', 'data' => $pst]);
    }

    public function update(Request $request, $id)
    {
        $pst = YuvaPst::findOrFail($id);

        $request->validate([
            'name'  => 'required|string|max:255',
            'post'  => 'required|in:अध्यक्ष,महामंत्री,कोषाध्यक्ष,सह कोषाध्यक्ष|unique:yuva_pst,post,' . $id,
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:200',
        ]);

        if ($request->hasFile('photo')) {
            if ($pst->photo) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $pst->photo));
            }
            $path = $request->file('photo')->store('yuva_pst', 'public');
            $pst->photo = '/storage/' . $path;
        }

        $pst->update([
            'name' => $request->name,
            'post' => $request->post,
            'photo'=> $pst->photo
        ]);

        return response()->json(['message' => 'Post updated successfully']);
    }

    public function destroy($id)
    {
        $pst = YuvaPst::findOrFail($id);

        if ($pst->photo) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $pst->photo));
        }
        $pst->delete();

        return response()->json(['message' => 'Post deleted successfully']);
    }
}
