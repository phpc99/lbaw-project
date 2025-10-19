<?php

namespace App\Listeners;

use App\Events\PurchaseCompleted;
use App\Models\AdminNews;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class NotifyAdminOfPurchase
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
    public function handle(PurchaseCompleted $event): void
    {
        $purchase = $event->purchase;
        $user = User::findOrFail($purchase->user_id);
        $total = number_format($purchase->total, 2);

        DB::insert('
            INSERT INTO admin_news (title, content, created_at, users_id)
            VALUES (?, ?, ?, ?)
        ', [
            'New Purchase Completed',
            "User {$user->name} (Email: {$user->email}) has made a purchase totaling \${$total}.",
            now(),  
            $user->user_id,
        ]);

        $orderDetails = $purchase->products->map(function ($product) {
            return "{$product->name} (Quantity: {$product->pivot->quantity})";
        })->join(', ');

        DB::insert('
            INSERT INTO admin_news (title, content, category, created_at, users_id)
            VALUES (?, ?, ?, ?, ?)
        ', [
            'New Order Placed',
            "User {$user->name} (Email: {$user->email}) placed an order. Details: {$orderDetails}. Total: \$" . number_format($purchase->total, 2),
            'order',
            now(),  
            $user->user_id,
        ]);

        /*AdminNews::create([
            'title' => 'New Purchase Completed',
            'content' => "User {$user->name} (Email: {$user->email}) has made a purchase totaling \${$total}.",
            'created_at' => now(),
            'users_id' => $user->user_id
        ]);
        AdminNews::create([
            'title' => 'New Order Placed',
            'content' => "User {$user->name} (Email: {$user->email}) placed an order. Details: {$orderDetails}. Total: \$" . number_format($purchase->total, 2),
            'category' => 'order',
            'created_at' => now(),
        ]);*/

    }
}
