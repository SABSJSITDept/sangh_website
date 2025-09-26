<?php

namespace App\Http\Controllers\YuvaSangh\GeneralDetails;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\YuvaSangh\GeneralUpdates\Content;
use Validator;

class ContentController extends Controller
{
    // Get all content
    public function index()
    {
        $contents = Content::latest()->get();
        return response()->json($contents);
    }

    // Store content
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $content = Content::create([
            'content' => $request->content,
        ]);

        return response()->json(['message' => 'Content added successfully', 'content' => $content]);
    }

    // Update content
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $content = Content::findOrFail($id);
        $content->update(['content' => $request->content]);

        return response()->json(['message' => 'Content updated successfully', 'content' => $content]);
    }

    // Delete content
    public function destroy($id)
    {
        $content = Content::findOrFail($id);
        $content->delete();

        return response()->json(['message' => 'Content deleted successfully']);
    }
}
