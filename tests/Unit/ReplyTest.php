<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReplyTest extends TestCase
{
    use DatabaseMigrations;

    /**
    * @test
    */
    public function is_has_an_owner()
    {
        $reply = create('App\Reply');

        $this->assertInstanceOf('App\User', $reply->owner);
    }

    /**
     * @test
     */
    public function a_user_can_delete_his_reply()
    {
        $user = create('App\User');

        $this->signIn($user);

        $reply = create('App\Reply', ['user_id' => $user->id]);

        $updatedVariable = 'Changed reply';

        $this->json('put', route('reply.update', [$reply->id]), ['body' => $updatedVariable]);

        $this->assertDatabaseHas('replies', ['id' => $reply->id, 'body' => $updatedVariable]);

    }

    /**
     * @test
     */
    public function a_user_can_delete_only_own_reply()
    {
        $user = create('App\User');

        $this->signIn($user);

        $reply = create('App\Reply');

        $updatedVariable = 'Changed reply';

        $this->json('put', route('reply.update', [$reply->id]), ['body' => $updatedVariable]);

        $this->assertDatabaseMissing('replies', ['id' => $reply->id, 'body' => $updatedVariable]);
    }
    
    /**
    * @test
    */
    public function it_wraps_mentioned_username_in_the_body_within_anchor_tags()
    {
        $reply = new \App\Reply([
            'body' => 'Hello @Alex.'
        ]);

        $this->assertEquals(
            'Hello <a href="/profile/Alex">@Alex</a>.',
            $reply->body
        );
    }
}
