<?php

namespace App\Listeners;

use App\Events\NotificationMarkedAsRead;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;

class UpdateNotificationCache
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(NotificationMarkedAsRead $event): void
    {
        Cache::forget("notification_count_" . $event->user_id);
    }
}
