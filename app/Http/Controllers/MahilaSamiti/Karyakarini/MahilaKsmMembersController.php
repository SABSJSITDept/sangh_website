<?php

namespace App\Http\Controllers\MahilaSamiti\Karyakarini;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MahilaSamiti\Karyakarini\MahilaKsmMember;
use App\Models\Aanchal\Aanchal;
use Illuminate\Support\Facades\Storage;

class MahilaKsmMembersController extends Controller
{
  public function index()
{
    $members = MahilaKsmMember::with('aanchal')
        ->orderBy('aanchal_id', 'asc')   // ✅ पहले aanchal_id के हिसाब से
        ->orderBy('id', 'desc')          // ✅ फिर उस aanchal के अंदर latest members पहले
        ->get();

    return response()->json($members);
}



    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'city'   => 'required|string|max:255',
            'mobile' => 'required|digits:10',
            'aanchal_id' => 'required|exists:aanchal,id',
            'photo'  => 'required|image|max:200', // 200kb
        ]);

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('mahila_ksm_members', 'public');
        }

        $member = MahilaKsmMember::create([
            'name' => $request->name,
            'city' => $request->city,
            'mobile' => $request->mobile,
            'aanchal_id' => $request->aanchal_id,
            'photo' => '/storage/' . $photoPath,
        ]);

        return response()->json(['success' => true, 'member' => $member]);
    }

    public function update(Request $request, $id)
    {
        $member = MahilaKsmMember::findOrFail($id);

        $request->validate([
            'name'   => 'required|string|max:255',
            'city'   => 'required|string|max:255',
            'mobile' => 'required|digits:10,' . $id,
            'aanchal_id' => 'required|exists:aanchal,id',
            'photo'  => 'nullable|image|max:200',
        ]);

        if ($request->hasFile('photo')) {
            if ($member->photo && file_exists(public_path($member->photo))) {
                unlink(public_path($member->photo));
            }
            $photoPath = $request->file('photo')->store('mahila_ksm_members', 'public');
            $member->photo = '/storage/' . $photoPath;
        }

        $member->update([
            'name' => $request->name,
            'city' => $request->city,
            'mobile' => $request->mobile,
            'aanchal_id' => $request->aanchal_id,
            'photo' => $member->photo,
        ]);

        return response()->json(['success' => true, 'member' => $member]);
    }
public function show($id)
{
    $member = MahilaKsmMember::with('aanchal')->findOrFail($id);
    return response()->json($member);
}

    public function destroy($id)
    {
        $member = MahilaKsmMember::findOrFail($id);
        if ($member->photo && file_exists(public_path($member->photo))) {
            unlink(public_path($member->photo));
        }
        $member->delete();
        return response()->json(['success' => true]);
    }
}
