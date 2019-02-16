<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ParticipateInForum extends TestCase
{
    use DatabaseMigrations;

    /**
    * @expectedException Illuminate\Auth\AuthenticationException
    */
    public function unauthenticated_users_may_not_add_replies()
    {
        $thread = factory('App\Thread')->create();
        $reply = factory('App\Reply')->create();
        $this->post(route('reply.store', $thread->id), $reply->toArray());
    }
    
    /**
    * @test
    */
    public function an_authenticated_user_may_participate_in_forum_threads()
    {
        // create user and authenticated him
        $this->signIn(factory('App\User')->create());

        // create a thread
        $thread = factory('App\Thread')->create();

        // create reply
        $reply = factory('App\Reply')->make();
        $this->post(route('reply.store', $thread->id), $reply->toArray());

        $this->get(route('threads.show', $thread->id))
            ->assertSee($reply->body);
    }
}
