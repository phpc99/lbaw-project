<?php

namespace App\Listeners;

use App\Events\CartItemAdded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;

class UpdateCartCache
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
    public function handle(CartItemAdded $event): void
    {
        Cache::forget("cart_count_" . $event->user_id);
    }
}
