<?php

namespace App\Http\Controllers\MahilaSamiti\Karyakarini;

use App\Http\Controllers\Controller;
use App\Models\MahilaSamiti\Karyakarini\MahilaExPrsident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MahilaExPrsidentController extends Controller
{
    // ✅ Fetch All (Latest First)
  public function index()
{
    return response()->json(MahilaExPrsident::oldest()->get());
}


    // ✅ Store
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'karyakal' => 'nullable|string',
            'place' => 'required|string',
            'photo' => 'required|image|max:200', // 200 KB
        ]);

        $path = $request->file('photo')->store('mahila_ex_prsident', 'public');

        $data = MahilaExPrsident::create([
            'name' => $request->name,
            'karyakal' => $request->karyakal,
            'place' => $request->place,
            'photo' => '/storage/' . $path,
        ]);

        return response()->json(['success' => true, 'data' => $data]);
    }

    // ✅ Update
    public function update(Request $request, $id)
    {
        $prsident = MahilaExPrsident::findOrFail($id);

        $request->validate([
            'name' => 'required|string',
            'karyakal' => 'nullable|string',
            'place' => 'required|string',
            'photo' => 'nullable|image|max:200',
        ]);

        if ($request->hasFile('photo')) {
            if ($prsident->photo && file_exists(public_path($prsident->photo))) {
                unlink(public_path($prsident->photo));
            }
            $path = $request->file('photo')->store('mahila_ex_prsident', 'public');
            $prsident->photo = '/storage/' . $path;
        }

        $prsident->update($request->only(['name', 'karyakal', 'place']));
        $prsident->save();

        return response()->json(['success' => true, 'data' => $prsident]);
    }

    // ✅ Delete
    public function destroy($id)
    {
        $prsident = MahilaExPrsident::findOrFail($id);

        if ($prsident->photo && file_exists(public_path($prsident->photo))) {
            unlink(public_path($prsident->photo));
        }

        $prsident->delete();

        return response()->json(['success' => true]);
    }
// ✅ Fetch Latest Entry
public function latest()
{
    $latest = MahilaExPrsident::latest()->first();
    return response()->json($latest);
}

}

