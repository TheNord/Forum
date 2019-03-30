<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class SubscribeToThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /**
    * @test
    */
    public function a_user_can_subscribe_to_threads()
    {
        $user = create('App\User');
        $this->signIn($user);

        $thread = create('App\Thread');

        $this->post(route('thread.subscribe', [$thread->channel->name, $thread->id]));

        $this->assertEquals(1, $thread->subscriptions()->count());
    }

    /**
    * @test
    */
    public function a_user_can_unsubscribe_from_threads()
    {
        $user = create('App\User');
        $this->signIn($user);

        $thread = create('App\Thread');

        $this->delete(route('thread.unsubscribe', [$thread->channel->name, $thread->id]));

        $this->assertEquals(0, $thread->subscriptions()->count());
    }
}
