<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class LockThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /**
    * @test
    */
    public function non_administrators_may_not_lock_threads()
    {
        $this->signIn(create('App\User'));

        $thread = create('App\Thread');

        $this->json('patch', $thread->path() . '/lock')
            ->assertStatus(403);

        $this->assertFalse($thread->fresh()->locked);
    }

    /**
    * @test
    */
    public function only_administrators_may_locked_threads()
    {
        $this->signIn(factory('App\User')->state('administrator')->create());

        $thread = create('App\Thread');

        $this->json('patch', $thread->path() . '/lock')
            ->assertStatus(200);

        $this->assertTrue($thread->fresh()->locked);
    }

    /**
    * @test
    */
    public function non_administrators_may_not_unlock_threads()
    {
        $this->signIn(create('App\User'));

        $thread = create('App\Thread', ['locked' => true]);

        $this->json('delete', $thread->path() . '/unlock')
            ->assertStatus(403);

        $this->assertTrue($thread->fresh()->locked);
    }

    /**
    * @test
    */
    public function only_administrators_may_unlocked_threads()
    {
        $this->signIn(factory('App\User')->state('administrator')->create());

        $thread = create('App\Thread', ['locked' => true]);

        $this->json('delete', $thread->path() . '/unlock')
            ->assertStatus(200);

        $this->assertFalse($thread->fresh()->locked);
    }

    /**
    * @test
    */
    public function locked_thread_may_not_receive_new_replies()
    {
        $thread = create('App\Thread');

        $thread->lock();

        $reply = make('App\Thread');

        $this->signIn(create('App\User'));

        $this->post(route('reply.store', [$thread->channel, $thread->slug]), $reply->toArray())
            ->assertStatus(422);
    }
}
