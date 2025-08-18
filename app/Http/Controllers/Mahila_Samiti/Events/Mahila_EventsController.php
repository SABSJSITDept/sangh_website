<?php

namespace App\Http\Controllers\Mahila_Samiti\Events;

use App\Http\Controllers\Controller;
use App\Models\Mahila_Samiti\Events\Mahila_Events;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class Mahila_EventsController extends Controller
{
    public function index()
    {
        return response()->json(Mahila_Events::all());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            'photo'   => 'nullable|image|max:200', // 200 KB
        ]);

        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 422);
        }

        $data = $request->only(['title','content']);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('mahila_events', 'public');
            $data['photo'] = $path;
        }

        $event = Mahila_Events::create($data);

        return response()->json(['success'=>'Event created','data'=>$event], 201);
    }

    public function update(Request $request, $id)
    {
        $event = Mahila_Events::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            'photo'   => 'nullable|image|max:200',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors'=>$validator->errors()], 422);
        }

        $event->title = $request->title;
        $event->content = $request->content;

        if ($request->hasFile('photo')) {
            if ($event->photo) {
                Storage::disk('public')->delete($event->photo);
            }
            $path = $request->file('photo')->store('mahila_events', 'public');
            $event->photo = $path;
        }

        $event->save();

        return response()->json(['success'=>'Event updated','data'=>$event], 200);
    }

    public function destroy($id)
    {
        $event = Mahila_Events::findOrFail($id);

        if ($event->photo) {
            Storage::disk('public')->delete($event->photo);
        }

        $event->delete();

        return response()->json(['success'=>'Event deleted'], 200);
    }
}
