<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class ShareNavbarCounts
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $userId = Auth::id();

            // Cache the counts for 1 minute
            $wishlistCount = Cache::remember("wishlist_count_" . $userId, 60, function () use ($userId) {
                return \App\Models\Wishlist::where('user_id', $userId)->count();
            });

            $cartCount = Cache::remember("cart_count_" . $userId, 60, function () use ($userId) {
                return \App\Models\Cart::where('user_id', $userId)->sum('quantity');
            });

            $notificationCount = Cache::remember("notification_count_" . $userId, 60, function () use ($userId) {
                return \App\Models\Notification::where('user_id', $userId)->whereIn('type', ['new_product', 'price_change', 'OrderStatusUpdated'])->where('is_read', false)->count();
            });

            view()->share(compact('wishlistCount', 'cartCount', 'notificationCount'));
        }

        return $next($request);
    }
}
