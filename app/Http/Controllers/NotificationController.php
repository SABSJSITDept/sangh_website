<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Contract\Messaging;
use App\Models\AppNotification;


class NotificationController extends Controller
{
    protected $messaging;

    public function __construct(Messaging $messaging)
    {
        $this->messaging = $messaging;
    }

public function sendNotification(Request $request)
{
    $group = $request->group ?? "Shree Sangh";
    $title = $request->title ?? "Default Title";
    $body = $request->body ?? "Default Body";
    $imageUrl = null;

    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('notifications', 'public');
        $imageUrl = asset('storage/' . $path);
    }

    // ✅ Clean text only (no HTML)
    $plainBody = strip_tags($body);

    // ✅ Save plain text in DB
    AppNotification::create([
        'group' => $group,
        'title' => $title,
        'body'  => $plainBody,
        'image' => $imageUrl,
    ]);

    // ✅ FCM Title (Group + Title)
    $finalTitle = $group . " - " . $title;

    if ($imageUrl) {
        $message = CloudMessage::new()
            ->withNotification(Notification::create($finalTitle, $plainBody, $imageUrl))
            ->withChangedTarget('topic', 'allUsers');
    } else {
        $message = CloudMessage::new()
            ->withNotification(Notification::create($finalTitle, $plainBody))
            ->withChangedTarget('topic', 'allUsers');
    }

    $this->messaging->send($message);

    return response()->json(['status' => 'Notification Sent & Saved!']);
}


public function listNotifications(Request $request)
{
    $year = $request->query('year');
    $month = $request->query('month');

    $query = AppNotification::query();

    if ($year) {
        $query->whereYear('created_at', $year);
    }
    if ($month) {
        $query->whereMonth('created_at', $month);
    }

    $notifications = $query->orderBy('created_at', 'desc')->get();

    return response()->json($notifications);
}
public function last30Days()
{
    $notifications = AppNotification::where('created_at', '>=', now()->subDays(30))
        ->orderBy('created_at', 'desc')
        ->get();

    return response()->json($notifications);
}
public function filterByGroupYearMonth(Request $request)
{
    $request->validate([
        'group' => 'required|string',   // e.g. "shree_sangh"
        'year'  => 'nullable|integer',
        'month' => 'nullable|integer|min:1|max:12',
    ]);

    $query = AppNotification::query()
        ->where('group', $request->group);

    if ($request->year) {
        $query->whereYear('created_at', $request->year);
    }

    if ($request->month) {
        $query->whereMonth('created_at', $request->month);
    }

    $notifications = $query->orderBy('created_at', 'desc')->get();

    return response()->json($notifications);
}

}
