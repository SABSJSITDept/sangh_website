<?php

namespace App\Http\Controllers\ShreeSangh\SanghPravartiya\Jsp;

use App\Models\ShreeSangh\SanghPravartiya\Jsp\JspHindiBooks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class JspHindiBooksController extends Controller
{
    public function index()
    {
        return response()->json(JspHindiBooks::latest()->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'pdf' => 'required|mimes:pdf|max:2048',
        ]);

        $pdfPath = $request->file('pdf')->store('jsp_hindi_books', 'public');

        JspHindiBooks::create([
            'name' => $request->name,
            'pdf' => $pdfPath,
        ]);

        return response()->json(['message' => 'बुक जोड़ी गई']);
    }

    public function update(Request $request, $id)
    {
        $book = JspHindiBooks::findOrFail($id);

        $request->validate([
            'name' => 'required|string',
            'pdf' => 'nullable|mimes:pdf|max:2048',
        ]);

        if ($request->hasFile('pdf')) {
            Storage::disk('public')->delete($book->pdf);
            $book->pdf = $request->file('pdf')->store('jsp_hindi_books', 'public');
        }

        $book->name = $request->name;
        $book->save();

        return response()->json(['message' => 'बुक अपडेट हुई']);
    }

    public function destroy($id)
    {
        $book = JspHindiBooks::findOrFail($id);
        Storage::disk('public')->delete($book->pdf);
        $book->delete();

        return response()->json(['message' => 'बुक हटाई गई']);
    }
}
