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
    * @test
    */
    public function unauthenticated_users_may_not_add_replies()
    {
        $thread = create('App\Thread');
        $reply = create('App\Reply');

        $response = $this->post(route('reply.store', [$thread->channel, $thread->id]), $reply->toArray());
        $this->assertEquals(302, $response->getStatusCode());
    }
    
    /**
    * @test
    */
    public function an_authenticated_user_may_participate_in_forum_threads()
    {
        // create user and authenticated him
        $this->signIn(create('App\User'));

        // create a thread
        $thread = create('App\Thread');

        // create reply
        $reply = make('App\Reply');

        $this->post(route('reply.store', [$thread->channel, $thread->id]), $reply->toArray());

        $this->get("/thread/{$thread->id}/replies")
            ->assertSee($reply->body);
    }
    
    /**
    * @test
    */
    public function a_reply_requires_a_body()
    {
        $this->signIn(create('App\User'));

        $thread = create('App\Thread');

        $this->post(route('reply.store', [$thread->channel, $thread]))
            ->assertSessionHasErrors(['body']);
    }
}
