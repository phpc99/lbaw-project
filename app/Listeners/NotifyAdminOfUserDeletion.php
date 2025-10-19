<?php

namespace App\Listeners;

use App\Models\AdminNews;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class NotifyAdminOfUserDeletion
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
    public function handle(object $event): void
    {
        $user = $event->user;

        DB::insert('
            INSERT INTO admin_news (title, content, created_at, users_id)
            VALUES (?, ?, ?, ?)
        ', [
            'User Account Deleted',
            "The user '{$user->name}' (Email: {$user->email}) has deleted their account.",
            now(),  // or you can replace `now()` with a specific timestamp if needed
            $user->user_id,
        ]);


        /*AdminNews::create([
            'title' => 'User Account Deleted',
            'content' => "The user '{$user->name}' (Email: {$user->email}) has deleted their account.",
            'created_at' => now(),
            'users_id' => $user->user_id
        ]);*/
    }
}
