<?php

namespace App\Http\Controllers\MahilaSamiti\Karyakarini;

use App\Http\Controllers\Controller;
use App\Models\MahilaSamiti\Karyakarini\MahilaVpSec;
use App\Models\Aanchal\Aanchal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MahilaVpSecController extends Controller
{
    public function index()
    {
        $data = MahilaVpSec::with('aanchal')
            ->orderBy('aanchal_id')
            ->orderBy('post')
            ->get();

        return response()->json($data);
    }

public function store(Request $request)
{
    $request->validate([
        'name'   => 'required|string|max:255',
        'post'   => 'required|in:उपाध्यक्ष,मंत्री',
        'city'   => 'nullable|string|max:255',
        'mobile' => 'nullable|string|max:15',
        'aanchal_id' => 'required|exists:aanchal,id',
        'photo'  => 'required|image|max:200', // 200 KB
    ]);

    // ✅ Check duplicate manually (better error msg than SQL)
    if (MahilaVpSec::where('aanchal_id', $request->aanchal_id)
        ->where('post', $request->post)
        ->exists()) {
        return response()->json([
            'success' => false,
            'message' => "इस आंचल में पहले से एक {$request->post} मौजूद है"
        ], 422);
    }

    $path = $request->file('photo')->store('mahila_vp_sec', 'public');

    $data = MahilaVpSec::create([
        'name'   => $request->name,
        'post'   => $request->post,
        'city'   => $request->city,
        'mobile' => $request->mobile,
        'aanchal_id' => $request->aanchal_id,
        'photo'  => '/storage/' . $path,
    ]);

    return response()->json(['success' => true, 'data' => $data]);
}


    public function update(Request $request, $id)
    {
        $vpSec = MahilaVpSec::findOrFail($id);

        $request->validate([
            'name'   => 'required|string|max:255',
            'post'   => 'required|in:उपाध्यक्ष,मंत्री',
            'city'   => 'nullable|string|max:255',
            'mobile' => 'nullable|string|max:15',
            'aanchal_id' => 'required|exists:aanchal,id',
            'photo'  => 'nullable|image|max:200',
        ]);

        if ($request->hasFile('photo')) {
            if ($vpSec->photo && file_exists(public_path($vpSec->photo))) {
                unlink(public_path($vpSec->photo));
            }
            $path = $request->file('photo')->store('mahila_vp_sec', 'public');
            $vpSec->photo = '/storage/' . $path;
        }

        $vpSec->update($request->only(['name','post','city','mobile','aanchal_id']));

        return response()->json(['success' => true, 'data' => $vpSec]);
    }

    // उपयुक्त use बात पहले से है
public function show($id)
{
    // id से रिकॉर्ड लाओ, नहीं मिला तो 404 दे देगा
    $item = MahilaVpSec::with('aanchal')->findOrFail($id);

    return response()->json($item);
}


    public function destroy($id)
    {
        $vpSec = MahilaVpSec::findOrFail($id);
        if ($vpSec->photo && file_exists(public_path($vpSec->photo))) {
            unlink(public_path($vpSec->photo));
        }
        $vpSec->delete();

        return response()->json(['success' => true]);
    }
}
