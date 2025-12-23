<?php

namespace App\Http\Controllers\Shree_sangh\Karyakarini;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShreeSangh\Karyakarini\VpSec;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class VpSecController extends Controller
{
    public function index()
    {
        $customOrder = [
            'Mewar',
            'Bikaner Marwar',
            'Jaipur Beawar',
            'Madhya Pradesh',
            'Chattisgarh Odisha',
            'Karnataka Andhra Pradesh',
            'Tamil Nadu',
            'Mumbai-Gujarat-UAE',
            'Maharashtra Vidarbha Khandesh',
            'Bengal-Bihar-Nepal-Bhutan-Jharkhand-Aanshik Orissa',
            'Purvottar',
            'Delhi-Punjab-Hariyana-Uttari',
        ];

        $grouped = VpSec::orderByRaw("FIELD(post, 'उपाध्यक्ष', 'मंत्री')")
            ->get()
            ->groupBy('aanchal');

        // Custom sort by order
        $sorted = collect($customOrder)->map(function ($aanchalName) use ($grouped) {
            $group = $grouped->get($aanchalName, collect());
            // Ensure we always return a Collection
            return is_object($group) && method_exists($group, 'isNotEmpty') ? $group : collect();
        })->filter(function ($group) {
            return is_object($group) && method_exists($group, 'isNotEmpty') && $group->isNotEmpty();
        })->values();

        return $sorted;
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'post' => 'required',
            'city' => 'required',
            'aanchal' => 'nullable',
            'mobile' => 'required',
            'photo' => 'nullable|image|max:250',
            'session' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // ✅ Check duplicate पद in same आंचल
        if (!empty($request->aanchal) && !empty($request->post)) {
            $exists = VpSec::where('aanchal', $request->aanchal)
                ->where('post', $request->post)
                ->exists();

            if ($exists) {
                return response()->json([
                    'message' => '❌ इस आंचल में यह पद पहले से मौजूद है।'
                ], 422);
            }
        }

        $data = $request->only(['name', 'post', 'city', 'aanchal', 'mobile', 'session']);

        // Ensure aanchal is set to null if empty
        if (empty($data['aanchal'])) {
            $data['aanchal'] = null;
        }

        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $extension = $image->getClientOriginalExtension();
            $filename = 'vp_sec/' . uniqid() . '.' . $extension;

            $resizedImage = $this->resizeImage($image->getPathname(), $extension, 500);
            Storage::disk('public')->put($filename, $resizedImage);

            $data['photo'] = $filename;
        }

        $vpSec = VpSec::create($data);

        return response()->json($vpSec, 201);
    }

    public function update(Request $request, $id)
    {
        $vpSec = VpSec::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'post' => 'required',
            'city' => 'required',
            'aanchal' => 'nullable',
            'mobile' => 'required',
            'photo' => 'nullable|image|max:250',
            'session' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // ✅ Check duplicate पद in same आंचल (excluding self)
        if (!empty($request->aanchal) && !empty($request->post)) {
            $exists = VpSec::where('aanchal', $request->aanchal)
                ->where('post', $request->post)
                ->where('id', '!=', $vpSec->id)
                ->exists();

            if ($exists) {
                return response()->json([
                    'message' => '❌ इस आंचल में यह पद पहले से मौजूद है।'
                ], 422);
            }
        }

        $data = $request->only(['name', 'post', 'city', 'aanchal', 'mobile', 'session']);

        // Ensure aanchal is set to null if empty
        if (empty($data['aanchal'])) {
            $data['aanchal'] = null;
        }

        if ($request->hasFile('photo')) {
            if ($vpSec->photo) {
                Storage::disk('public')->delete($vpSec->photo);
            }

            $image = $request->file('photo');
            $extension = $image->getClientOriginalExtension();
            $filename = 'vp_sec/' . uniqid() . '.' . $extension;

            $resizedImage = $this->resizeImage($image->getPathname(), $extension, 500);
            Storage::disk('public')->put($filename, $resizedImage);

            $data['photo'] = $filename;
        }

        $vpSec->update($data);

        return response()->json($vpSec);
    }

    public function destroy($id)
    {
        $vpSec = VpSec::findOrFail($id);

        if ($vpSec->photo) {
            Storage::disk('public')->delete($vpSec->photo);
        }

        $vpSec->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }

    /**
     * Resize image using GD (no external package)
     */
    private function resizeImage($path, $extension, $maxWidth)
    {
        list($width, $height) = getimagesize($path);
        $newWidth = $maxWidth;
        $newHeight = intval($height * $newWidth / $width);

        $src = null;
        switch (strtolower($extension)) {
            case 'jpg':
            case 'jpeg':
                $src = imagecreatefromjpeg($path);
                break;
            case 'png':
                $src = imagecreatefrompng($path);
                break;
            case 'gif':
                $src = imagecreatefromgif($path);
                break;
            default:
                return file_get_contents($path);
        }

        $dst = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        ob_start();
        if (in_array(strtolower($extension), ['jpg', 'jpeg'])) {
            imagejpeg($dst, null, 80);
        } elseif ($extension === 'png') {
            imagepng($dst, null, 8);
        } elseif ($extension === 'gif') {
            imagegif($dst);
        }
        $imageData = ob_get_clean();

        imagedestroy($src);
        imagedestroy($dst);

        return $imageData;
    }
}
