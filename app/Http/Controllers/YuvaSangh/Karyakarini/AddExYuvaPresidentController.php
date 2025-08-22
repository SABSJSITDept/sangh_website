<?php

namespace App\Http\Controllers\YuvaSangh\Karyakarini;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\YuvaSangh\Karyakarini\YuvaExPresident;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AddExYuvaPresidentController extends Controller
{
   public function index()
{
    return response()->json(
        YuvaExPresident::orderBy('created_at', 'desc')->get()
    );
}


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required|string|max:255',
            'photo' => 'required|image|max:200', 
            'karyakal' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()],422);
        }

        $path = $request->file('photo')->store('yuva_ex_president','public');

        $data = YuvaExPresident::create([
            'name' => $request->name,
            'karyakal' => $request->karyakal,
            'city' => $request->city,
            'photo' => "/storage/".$path,
        ]);

        return response()->json(['message'=>'Created successfully','data'=>$data]);
    }

    public function update(Request $request, $id)
    {
        $item = YuvaExPresident::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name'  => 'required|string|max:255',
            'photo' => 'nullable|image|max:200',
            'karyakal' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()],422);
        }

        if ($request->hasFile('photo')) {
            if ($item->photo && file_exists(public_path($item->photo))) {
                unlink(public_path($item->photo));
            }
            $path = $request->file('photo')->store('yuva_ex_president','public');
            $item->photo = "/storage/".$path;
        }

        $item->update([
            'name' => $request->name,
            'karyakal' => $request->karyakal,
            'city' => $request->city,
            'photo' => $item->photo,
        ]);

        return response()->json(['message'=>'Updated successfully','data'=>$item]);
    }

    public function destroy($id)
    {
        $item = YuvaExPresident::findOrFail($id);
        if ($item->photo && file_exists(public_path($item->photo))) {
            unlink(public_path($item->photo));
        }
        $item->delete();

        return response()->json(['message'=>'Deleted successfully']);
    }
}
