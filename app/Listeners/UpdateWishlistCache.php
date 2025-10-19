<?php

namespace App\Listeners;

use App\Events\WishlistItemAdded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;

class UpdateWishlistCache
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
    public function handle(WishlistItemAdded $event): void
    {
        Cache::forget("wishlist_count_" . $event->user_id);
    }
}
