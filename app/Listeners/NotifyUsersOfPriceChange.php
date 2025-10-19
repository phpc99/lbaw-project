<?php

namespace App\Listeners;

use App\Events\PriceChangedEvent;
use App\Models\Cart;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyUsersOfPriceChange implements ShouldQueue
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
    public function handle(PriceChangedEvent $event): void
    {
        $product = $event->product;
        $oldPrice = $event->oldPrice;
        $cartItems = Cart::where('product_id', $product->product_id)->get();

        foreach ($cartItems as $item) {
            Notification::create([
                'user_id' => $item->user_id,
                'type' => 'price_change',
                'data' => [
                    'product_id' => $product->product_id,
                    'product_name' => $product->name,
                    'old_price' => $oldPrice,
                    'new_price' => $product->price
                ]
            ]);
        }
    }
}
