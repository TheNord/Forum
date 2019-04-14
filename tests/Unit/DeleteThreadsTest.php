<?php

namespace Tests\Unit;

use App\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /**
    /**
    * @test
    */
    public function thread_can_be_deleted()
    {
        $user = create('App\User');

        $this->signIn($user);

        $thread = create('App\Thread', ['user_id' => $user->id]);

        $this->json('delete', route('threads.delete', [$thread->channel, $thread->slug]));

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);

        $this->assertDatabaseMissing('activities', [
            'subject_id' => $thread->id,
            'subject_type' => get_class($thread)
        ]);

    }

    /**
     * @test
     */
    public function thread_can_be_deleted_only_owner()
    {
        $user = create('App\User');

        $this->signIn($user);

        $thread = create('App\Thread');

        $this->json('delete', route('threads.delete', [$thread->channel, $thread->id]));

        $this->assertDatabaseHas('threads', ['id' => $thread->id]);

    }

    /**
    * @test
    */
    public function thread_with_replies_can_not_be_deleted()
    {
        $user = create('App\User');

        $this->signIn($user);

        $thread = create('App\Thread', ['user_id' => $user->id]);

        create('App\Reply', ['thread_id' => $thread->id]);

        $this->delete(route('threads.delete', [$thread->channel, $thread->id]));

        $this->assertDatabaseHas('threads', ['id' => $thread->id]);

    }
}
