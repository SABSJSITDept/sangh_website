<?php
namespace App\Http\Controllers\Shree_sangh\Karyakarini;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShreeSangh\Karyakarini\ExPresident;
use Illuminate\Support\Facades\Storage;

class ExPresidentController extends Controller
{
    public function index()
    {
        return view('dashboards.shree_sangh.karyakarini.ex_president');
    }

public function all()
{
    return response()->json(
        ExPresident::orderBy('created_at', 'asc')->get()
    );
}



    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'place' => 'required|string',
            'karaykal' => 'required|string',
            'photo' => 'required|image|max:200'
        ]);

        $path = $request->file('photo')->store('ex_presidents', 'public');

        $data = ExPresident::create([
            'name' => $request->name,
            'place' => $request->place,
            'karaykal' => $request->karaykal,
            'photo' => $path
        ]);

        return response()->json(['message' => 'Added Successfully', 'data' => $data]);
    }

    public function update(Request $request, $id)
    {
        $data = ExPresident::findOrFail($id);

        $request->validate([
            'name' => 'required|string',
            'place' => 'required|string',
            'karaykal' => 'required|string',
            'photo' => 'nullable|image|max:200'
        ]);

        if ($request->hasFile('photo')) {
            if ($data->photo) {
                Storage::disk('public')->delete($data->photo);
            }
            $data->photo = $request->file('photo')->store('ex_presidents', 'public');
        }

        $data->update([
            'name' => $request->name,
            'place' => $request->place,
            'karaykal' => $request->karaykal,
            'photo' => $data->photo,
        ]);

        return response()->json(['message' => 'Updated Successfully', 'data' => $data]);
    }

    public function destroy($id)
    {
        $data = ExPresident::findOrFail($id);
        Storage::disk('public')->delete($data->photo);
        $data->delete();

        return response()->json(['message' => 'Deleted Successfully']);
    }
}
