<?php

// app/Http/Controllers/ShreeSangh/SanghPravartiya/Jsp/JspGujratiBooksController.php
namespace App\Http\Controllers\ShreeSangh\SanghPravartiya\Jsp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShreeSangh\SanghPravartiya\Jsp\JspGujratiBooks;
use Illuminate\Support\Facades\Storage;

class JspGujratiBooksController extends Controller
{
public function index()
{
    return response()->json(JspGujratiBooks::orderBy('preference')->get());
}

public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'preference' => 'required|integer|unique:jsp_gujrati_books,preference',
        'pdf' => 'required|mimes:pdf|max:2048',
    ]);

    $path = $request->file('pdf')->store('jsp_gujrati_books', 'public');

    $book = JspGujratiBooks::create([
        'name' => $request->name,
        'preference' => $request->preference,
        'pdf' => $path,
    ]);

    return response()->json(['message' => 'Added Successfully', 'data' => $book], 201);
}

public function update(Request $request, $id)
{
    $book = JspGujratiBooks::findOrFail($id);

    $request->validate([
        'name' => 'required|string|max:255',
        'preference' => 'required|integer|unique:jsp_gujrati_books,preference,' . $id,
        'pdf' => 'nullable|mimes:pdf|max:2048',
    ]);

    if ($request->hasFile('pdf')) {
        Storage::disk('public')->delete($book->pdf);
        $book->pdf = $request->file('pdf')->store('jsp_gujrati_books', 'public');
    }

    $book->name = $request->name;
    $book->preference = $request->preference;
    $book->save();

    return response()->json(['message' => 'Updated Successfully']);
}

    public function destroy($id)
    {
        $book = JspGujratiBooks::findOrFail($id);
        Storage::disk('public')->delete($book->pdf);
        $book->delete();

        return response()->json(['message' => 'Deleted Successfully']);
    }
}
