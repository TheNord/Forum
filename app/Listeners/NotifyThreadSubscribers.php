<?php

namespace App\Listeners;

class NotifyThreadSubscribers
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $event->thread->subscriptions
            ->where('user_id', '!=', $event->reply->user_id)
            ->each
            ->notify($event->thread, $event->reply);
    }
}
