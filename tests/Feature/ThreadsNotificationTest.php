<?php

namespace Tests\Feature;

use App\Notifications\ThreadWasUpdated;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ThreadsNotificationTest extends TestCase
{
    use DatabaseMigrations;

    /**
    * @test
    */
    public function a_user_receive_new_reply_thread_notification()
    {
        $user = create('App\User');
        $this->signIn($user);

        $thread = create('App\Thread');

        $this->post(route('thread.subscribe', [$thread->channel->name, $thread->id]));

        $thread->addReply([
            'user_id' => create('App\User')->id,
            'body' => 'Some reply',
        ]);

        $this->assertEquals(1, $user->notifications()->count());
    }
    
    /**
    * @test
    */
    public function a_user_can_fetch_their_unread_notifications()
    {
        $user = create('App\User');
        $this->signIn($user);

        $thread = create('App\Thread');

        $this->post(route('thread.subscribe', [$thread->channel->name, $thread->id]));

        $reply = $thread->addReply([
            'user_id' => create('App\User')->id,
            'body' => 'Some reply',
        ]);

        $this->get(route('notifications'))
            ->assertSee($reply->title);
    }
    
    /**
    * @test
    */
    public function a_user_can_mark_a_notifications_as_read()
    {
        $user = create('App\User');
        $this->signIn($user);

        $thread = create('App\Thread');

        $this->post(route('thread.subscribe', [$thread->channel->name, $thread->id]));

        $thread->addReply([
            'user_id' => create('App\User')->id,
            'body' => 'Some reply',
        ]);

        $this->assertCount(1, $user->unreadNotifications);

        $notificationId = $user->unreadNotifications->first()->id;

        $this->delete(route('notifications.read', $notificationId));

        $this->assertCount(0, $user->fresh()->unreadNotifications);
    }
}
