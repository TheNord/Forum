<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class MentionUsersTest extends TestCase
{
    use DatabaseMigrations;
    
    /**
    * @test
    */
    public function mention_users_in_a_reply_are_notified()
    {
        $userAlex = create('App\User', ['name' => 'Alex']);
        $this->signIn($userAlex);

        $userJohn = create('App\User', ['name' => 'John']);

        $thread = create('App\Thread');

        $reply = make('App\Reply', [
            'body' => 'Hello @John',
        ]);

        $this->json('post', route('reply.store', [$thread->channel, $thread->id]), $reply->toArray());

        $this->assertCount(1, $userJohn->notifications);
    }

    /**
    * @test
    */
    public function it_can_fetch_all_mentioned_users_starting_with_the_given_characters()
    {
        create('App\User', ['name' => 'Alex']);

        $this->json('GET', '/api/users', ['name' => 'Al'])
            ->assertSee('Alex');
    }
}
