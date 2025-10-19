<?php

namespace App\Listeners;

use App\Events\OrderStatusUpdated;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyUserOfOrderStatus
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
    public function handle(OrderStatusUpdated $event)
    {
        $order = $event->order;

        if ($order->user_id) {
            Notification::insert([
                'user_id' => $order->user_id,
                'type' => 'OrderStatusUpdated',
                'data' => json_encode([
                    'order_id' => $order->purchase_id,
                    'status' => $order->delivery_progress,
                    'message' => "Your order #{$order->purchase_id} status has been updated to '{$order->delivery_progress}'.",
                ])
            ]);
        }
    }
}
