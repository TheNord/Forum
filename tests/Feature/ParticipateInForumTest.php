<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

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

        $response = $this->post(route('reply.store', [$thread->channel, $thread->slug]), $reply->toArray());
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

        $this->post(route('reply.store', [$thread->channel, $thread->slug]), $reply->toArray());

        $this->get("/thread/{$thread->slug}/replies")
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

    /**
     * @test
     */
    public function replies_that_contain_spam_may_not_be_created()
    {
        $this->signIn(create('App\User'));

        $thread = create('App\Thread');

        $reply = make('App\Reply', [
            'body' => 'Yahoo Customer Support'
        ]);

        $this->post(route('reply.store', [$thread->channel, $thread->slug]), $reply->toArray());

        $this->get("/thread/{$thread->id}/replies")
            ->assertDontSee($reply->body);
    }

    /**
     * @test
     */
    public function user_may_only_reply_a_maximum_of_once_per_minute()
    {
        $user = create('App\User');
        $this->signIn($user);

        $thread = create('App\Thread');

        $reply = make('App\Reply', [
            'body' => 'Simple reply body',
            'user_id' => $user
        ]);

        $this->post(route('reply.store', [$thread->channel, $thread->slug]), $reply->toArray())
            ->assertStatus(200);

        $this->post(route('reply.store', [$thread->channel, $thread->slug]), $reply->toArray())
            ->assertStatus(403);
    }
}
