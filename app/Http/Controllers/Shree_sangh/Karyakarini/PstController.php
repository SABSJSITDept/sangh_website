<?php

namespace App\Http\Controllers\Shree_sangh\Karyakarini;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShreeSangh\Karyakarini\Pst;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PstController extends Controller
{
    public function index()
    {
        $order = ['अध्यक्ष', 'महामंत्री', 'कोषाध्यक्ष', 'सह कोषाध्यक्ष'];
        return Pst::orderByRaw("FIELD(post, '" . implode("','", $order) . "')")->get();
    }

    public function store(Request $request)
    {
        // ✅ Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'post' => 'required|string|in:अध्यक्ष,महामंत्री,कोषाध्यक्ष,सह कोषाध्यक्ष',
            'session' => 'required|string|max:255',
            'photo' => 'required|image|max:200', // 200KB
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // 🔒 Prevent duplicate post in the same session
        if (
            Pst::where('post', $request->post)
                ->where('session', $request->input('session'))
                ->exists()
        ) {
            return response()->json(['error' => '❌ इस सत्र में यह पद पहले से ही जोड़ा जा चुका है।'], 403);
        }

        // 🔒 Limit check: Max 4 posts per session
        if (Pst::where('session', $request->input('session'))->count() >= 4) {
            return response()->json(['error' => '❌ इस सत्र में केवल 4 पद ही अनुमत हैं।'], 403);
        }

        // ✅ Save data
        $data = $request->only(['name', 'post', 'session']);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('pst', 'public');
        }

        $pst = Pst::create($data);

        return response()->json($pst);
    }


    public function update(Request $request, $id)
    {
        $pst = Pst::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'post' => 'required|string|max:255',
            'session' => 'required|string|max:255',
            'photo' => 'required|image|max:200', // 200KB
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // ✅ Check for duplicate post in the same session excluding current record
        $existing = Pst::where('post', $request->post)
            ->where('session', $request->input('session'))
            ->where('id', '!=', $id)
            ->first();
        if ($existing) {
            return response()->json(['error' => '❌ इस सत्र में यह पद पहले से किसी अन्य व्यक्ति के पास है।'], 403);
        }

        $pst->name = $request->name;
        $pst->post = $request->post;
        $pst->session = $request->input('session');

        if ($request->hasFile('photo')) {
            if ($pst->photo) {
                Storage::disk('public')->delete($pst->photo);
            }
            $pst->photo = $request->file('photo')->store('pst', 'public');
        }

        $pst->save();

        return response()->json($pst);
    }


    public function destroy($id)
    {
        $pst = Pst::findOrFail($id);
        if ($pst->photo) {
            Storage::disk('public')->delete($pst->photo);
        }
        $pst->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }

    public function show($id)
    {
        return Pst::findOrFail($id);
    }
}
