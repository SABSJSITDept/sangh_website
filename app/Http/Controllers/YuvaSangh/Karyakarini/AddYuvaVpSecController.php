<?php

namespace App\Http\Controllers\YuvaSangh\Karyakarini;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\YuvaSangh\Karyakarini\YuvaVpSec;

class AddYuvaVpSecController extends Controller
{
  public function index(Request $request)
{
    $q = YuvaVpSec::query();

    // Filters
    if ($request->filled('post')) {
        $q->where('post', $request->post);
    }

    if ($request->filled('aanchal')) {
        $q->where('aanchal', $request->aanchal);
    }

    // Custom Aanchal order
    $aanchalOrder = [
        "Mewar",
        "Bikaner Marwar",
        "Jaipur Beawar",
        "Madhya Pradesh",
        "Chattisgarh Odisha",
        "Karnataka Andhra Pradesh",
        "Tamil Nadu",
        "Mumbai-Gujarat-UAE",
        "Maharashtra Vidarbha Khandesh",
        "Bengal-Bihar-Nepal-Bhutan-Jharkhand-Aanshik Orissa",
        "Purvottar",
        "Delhi-Punjab-Hariyana-Uttari"
    ];

    // Use FIELD() function in MySQL to order by custom array
    $q->orderByRaw("FIELD(aanchal, '" . implode("','", $aanchalOrder) . "')")
      ->orderByDesc('id'); // inside same aanchal, latest first

    return response()->json($q->get());
}


    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'post'    => 'required|in:उपाध्यक्ष,मंत्री',
            'aanchal' => 'required|string|max:255',
            'city'    => 'nullable|string|max:255',
            'mobile'  => 'nullable|string|max:20',
            'photo'   => 'required|image|max:200', // 200KB
        ]);

        $path = $request->file('photo')->store('yuva_vp_sec', 'public');

        $vp = YuvaVpSec::create([
            'name' => $request->name,
            'post' => $request->post,
            'city' => $request->city,
            'aanchal' => $request->aanchal,
            'mobile' => $request->mobile,
            'photo' => '/storage/' . $path,
        ]);

        return response()->json(['message' => 'Entry Added Successfully', 'data' => $vp]);
    }

    public function update(Request $request, $id)
    {
        $vp = YuvaVpSec::findOrFail($id);

        $request->validate([
            'name'    => 'required|string|max:255',
            'post'    => 'required|in:उपाध्यक्ष,मंत्री',
            'aanchal' => 'required|string|max:255',
            'city'    => 'nullable|string|max:255',
            'mobile'  => 'nullable|string|max:20',
            'photo'   => 'nullable|image|max:200', // 200KB
        ]);

        if ($request->hasFile('photo')) {
            // पुरानी फ़ाइल हटाना हो तो यहां unlink logic लगा सकते हैं
            $path = $request->file('photo')->store('yuva_vp_sec', 'public');
            $vp->photo = '/storage/' . $path;
        }

        $vp->name = $request->name;
        $vp->post = $request->post;
        $vp->city = $request->city;
        $vp->aanchal = $request->aanchal;
        $vp->mobile = $request->mobile;
        $vp->save();

        return response()->json(['message' => 'Entry Updated Successfully', 'data' => $vp]);
    }

    public function destroy($id)
    {
        $vp = YuvaVpSec::findOrFail($id);
        $vp->delete();
        return response()->json(['message' => 'Entry Deleted Successfully']);
    }
}
