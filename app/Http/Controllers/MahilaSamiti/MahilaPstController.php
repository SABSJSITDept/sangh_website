<?php

namespace App\Http\Controllers\MahilaSamiti;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MahilaSamiti\MahilaPst;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class MahilaPstController extends Controller
{
    // सभी fetch (order wise: अध्यक्ष > महामंत्री > कोषाध्यक्ष > सह कोषाध्यक्ष)
    public function index()
    {
        $order = ['अध्यक्ष', 'महामंत्री', 'कोषाध्यक्ष', 'सह कोषाध्यक्ष'];
        $members = MahilaPst::orderByRaw("FIELD(post, '" . implode("','", $order) . "')")->get();
        return response()->json($members);
    }

    // नया सदस्य जोड़ना
   public function store(Request $request)
{
    $request->validate([
        'name'  => 'required|string|max:255',
        'post'  => 'required|in:अध्यक्ष,महामंत्री,कोषाध्यक्ष,सह कोषाध्यक्ष|unique:mahila_pst,post',
        'photo' => 'required|image|max:200'
    ], [
        'post.unique' => 'इस पद पर पहले से सदस्य जुड़ा हुआ है।',
        'photo.max'   => 'फोटो 200KB से ज़्यादा नहीं हो सकती।',
        'photo.image' => 'केवल फोटो अपलोड कर सकते हैं।'
    ]);

    $path = $request->file('photo')->store('mahila_pst', 'public');

    $member = MahilaPst::create([
        'name'  => $request->name,
        'post'  => $request->post,
        'photo' => '/storage/' . $path,
    ]);

    return response()->json(['success' => true, 'data' => $member]);
}

public function update(Request $request, $id)
{
    $member = MahilaPst::findOrFail($id);

    $request->validate([
        'name'  => 'required|string|max:255',
        'post'  => 'required|in:अध्यक्ष,महामंत्री,कोषाध्यक्ष,सह कोषाध्यक्ष|unique:mahila_pst,post,' . $member->id,
        'photo' => 'nullable|image|max:200'
    ], [
        'post.unique' => 'इस पद पर पहले से सदस्य जुड़ा हुआ है।',
        'photo.max'   => 'फोटो 200KB से ज़्यादा नहीं हो सकती।',
        'photo.image' => 'केवल फोटो अपलोड कर सकते हैं।'
    ]);

    $data = [
        'name' => $request->name,
        'post' => $request->post,
    ];

    if ($request->hasFile('photo')) {
        if ($member->photo) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $member->photo));
        }
        $path = $request->file('photo')->store('mahila_pst', 'public');
        $data['photo'] = '/storage/' . $path;
    }

    $member->update($data);

    return response()->json(['success' => true, 'data' => $member]);
}


    // डिलीट करना
    public function destroy($id)
    {
        $member = MahilaPst::findOrFail($id);
        if ($member->photo) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $member->photo));
        }
        $member->delete();
        return response()->json(['success' => true]);
    }
}
