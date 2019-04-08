<?php

namespace App\Listeners;

use App\Notifications\YouWereMentioned;
use App\User;

class NotifyMentionedUsers
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        preg_match_all('/\@([^\s\.]+)/', $event->reply->body, $matches);

        foreach ($matches[1] as $username) {
            $user = User::whereName($username)->first();

            if ($user) {
                $user->notify(new YouWereMentioned($event->reply));
            }
        }
    }
}
