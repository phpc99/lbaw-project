<?php

namespace App\Listeners;

use App\Events\NewProductAddedEvent;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyUsersOfNewProduct
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
    public function handle(NewProductAddedEvent $event): void
    {
        $product = $event->product;
        $users = User::all();

        foreach ($users as $user) {
            Notification::create([
                'user_id' => $user->user_id,
                'type' => 'new_product',
                'data' => [
                    'product_id' => $product->product_id,
                    'product_name' => $product->name,
                    'product_price' => $product->price
                ]
            ]);
        }


    }
}
