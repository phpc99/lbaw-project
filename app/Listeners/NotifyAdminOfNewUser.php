<?php

namespace App\Listeners;

use App\Models\AdminNews;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyAdminOfNewUser
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
    public function handle(object $event)
    {
        $user = $event->user;

        AdminNews::insert([
            'title' => 'New User Registered',
            'content' => "A new user, {$user->name}, has registered with email {$user->email}.",
            'created_at' => now(),
            'users_id' => $user->user_id 
        ]);
    }
}
