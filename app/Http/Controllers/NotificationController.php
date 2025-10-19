<?php

namespace App\Http\Controllers;

use App\Events\NotificationMarkedAsRead;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Psy\Readline\Hoa\EventBucket;

class NotificationController extends Controller
{
    public function list() {
        $notifications = Notification::where('user_id', Auth::id())->whereIn('type', ['new_product', 'price_change', 'OrderStatusUpdated'])->orderBy('created_at', 'desc')->get();
        
        return view('notifications.list', compact('notifications'));
    }

    public function markAsRead($id) {

        $notification = Notification::findOrFail($id);

        if ($notification->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        $notification->update(['is_read' => true]);

        event(new NotificationMarkedAsRead(Auth::id()));

        return redirect()->route('products.list');
    }
}
