<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Contract\Messaging;

class NotificationController extends Controller
{
    protected $messaging;

    public function __construct(Messaging $messaging)
    {
        $this->messaging = $messaging;
    }

public function sendNotification(Request $request)
{
    $title = $request->title ?? "Default Title";
    $body = $request->body ?? "Default Body";
    $imageUrl = null;

    // ✅ Agar image upload hui hai to storage me save karo
    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('notifications', 'public');
        $imageUrl = asset('storage/' . $path); // ✅ Public URL
    }

    // ✅ Notification banaao (agar image hai to use karlo)
    if ($imageUrl) {
        $message = CloudMessage::new()
            ->withNotification(Notification::create($title, $body, $imageUrl))
            ->withChangedTarget('topic', 'allUsers');
    } else {
        $message = CloudMessage::new()
            ->withNotification(Notification::create($title, $body))
            ->withChangedTarget('topic', 'allUsers');
    }

    $this->messaging->send($message);

    return response()->json(['status' => 'Notification Sent!']);
}


}
