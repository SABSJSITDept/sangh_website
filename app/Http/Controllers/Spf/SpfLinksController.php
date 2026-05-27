<?php

namespace App\Http\Controllers\Spf;

use App\Http\Controllers\Controller;
use App\Models\Spf\SpfLinks;
use Illuminate\Http\Request;

class SpfLinksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Retrieve the first record or create a default empty one
        $links = SpfLinks::firstOrCreate(
            ['id' => 1],
            [
                'mobile_number' => [],
                'email' => [],
                'whatsapp_number' => [],
                'website_link' => '',
                'registration_link' => '',
                'facebook_link' => '',
                'instagram_link' => '',
                'youtube_link' => '',
                'twitter_link' => '',
            ]
        );

        return response()->json([
            'success' => true,
            'data' => $links
        ]);
    }

    /**
     * Store or update the resource.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'mobile_number' => 'nullable|array',
            'mobile_number.*' => 'nullable|string|max:50',
            'email' => 'nullable|array',
            'email.*' => 'nullable|email|max:255',
            'whatsapp_number' => 'nullable|array',
            'whatsapp_number.*' => 'nullable|string|max:50',
            'website_link' => 'nullable|string|max:255',
            'registration_link' => 'nullable|string|max:255',
            'facebook_link' => 'nullable|string|max:255',
            'instagram_link' => 'nullable|string|max:255',
            'youtube_link' => 'nullable|string|max:255',
            'twitter_link' => 'nullable|string|max:255',
        ]);

        // Filter out empty or null items from arrays
        if (isset($validated['mobile_number'])) {
            $validated['mobile_number'] = array_values(array_filter($validated['mobile_number'], fn($val) => !is_null($val) && trim($val) !== ''));
        }
        if (isset($validated['whatsapp_number'])) {
            $validated['whatsapp_number'] = array_values(array_filter($validated['whatsapp_number'], fn($val) => !is_null($val) && trim($val) !== ''));
        }
        if (isset($validated['email'])) {
            $validated['email'] = array_values(array_filter($validated['email'], fn($val) => !is_null($val) && trim($val) !== ''));
        }

        $links = SpfLinks::updateOrCreate(
            ['id' => 1],
            $validated
        );

        return response()->json([
            'success' => true,
            'message' => 'Links and contact details updated successfully',
            'data' => $links
        ]);
    }
}
