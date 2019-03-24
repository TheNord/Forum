<?php

namespace Tests\Unit;

use App\Thread;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteReplyTest extends TestCase
{
    use DatabaseMigrations;

    /**
    /**
    * @test
    */
    public function reply_can_be_deleted()
    {
        $user = create('App\User');

        $this->signIn($user);

        $reply = create('App\Reply', ['user_id' => $user->id]);

        $this->json('delete', route('reply.delete', [$reply->thread->channel, $reply->thread->id, $reply->id]));

        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);

        $this->assertDatabaseMissing('replies', [
            'subject_id' => $reply->id,
            'subject_type' => get_class($reply)
        ]);

    }

    /**
     * @test
     */
    public function reply_can_be_deleted_only_owner()
    {
        $user = create('App\User');

        $this->signIn($user);

        $reply = create('App\Reply');

        $this->json('delete', route('threads.delete', [$reply->thread->channel, $reply->thread->id, $reply->id]));

        $this->assertDatabaseHas('replies', ['id' => $reply->id]);
    }
}
